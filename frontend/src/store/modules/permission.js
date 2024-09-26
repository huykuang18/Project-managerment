import { asyncRoutes, constantRoutes } from '@/router'
import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/**
 * Use meta.role to determine if the current user has permission
 * @param roles
 * @param route
 */
function hasPermission(roles, route) {
  if (route.meta && route.meta.roles) {
    return roles.some(role => route.meta.roles.includes(role))
  } else {
    return true
  }
}

/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param roles
 */
export function filterAsyncRoutes(routes, roles) {
  const res = []
  routes.forEach(route => {
    const tmp = { ...route }
    if (hasPermission(roles, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRoutes(tmp.children, roles)
      }
      res.push(tmp)
    }
  })

  return res
}

const state = {
  routes: [
    {
      path: '/',
      component: '',
      name: 'Dashboard',
      meta: { title: 'Dashboard', icon: 'dashboard' }
    },
    {
      path: 'user',
      component: '',
      name: 'User',
      meta: { title: 'Người dùng', icon: 'user' }
    },
    {
      path: 'project',
      component: '',
      name: 'Project',
      meta: { title: 'Dự án tham gia', icon: 'skill' }
    },
    {
      path: 'item',
      component: '',
      name: 'Item',
      meta: { title: 'Mục trong dự án', icon: 'clipboard' }
    },
    {
      path: 'issue',
      component: '',
      name: 'Issue',
      meta: { title: 'Công việc', icon: 'nested' }
    }
  ],
  addRoutes: []
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  }
}

const actions = {
  generateRoutes({ commit }, roles) {
    return new Promise(resolve => {
      let accessedRoutes
      if (roles.includes('admin')) {
        accessedRoutes = asyncRoutes || []
      } else {
        accessedRoutes = filterAsyncRoutes(asyncRoutes, roles)
      }
      commit('SET_ROUTES', accessedRoutes)
      resolve(accessedRoutes)
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
