import request from '@/utils/request'

export async function login(params) {
  const data = await request({
    url: '/login',
    method: 'post',
    params
  })
  return data
}

export async function getInfo(token) {
  const data = await request({
    url: '/user-info',
    method: 'get',
    params: { token }
  })
  return data
}

export async function logout(token) {
  const data = await request({
    url: '/logout',
    method: 'get',
    params: { token }
  })
  return data
}

export async function getUsers(query) {
  const data = await request({
    url: '/users',
    method: 'get',
    params: query
  })
  return data
}

export async function getUserOptions(project_id) {
  const data = await request({
    url: '/users/all',
    method: 'get',
    params: { project_id }
  })
  return data
}

export async function deleteUser(id) {
  const data = await request({
    url: '/user/delete/' + id,
    method: 'delete'
  })
  return data
}

export async function createUser(params) {
  const data = await request({
    url: '/register',
    method: 'post',
    data: {
      name: params.name,
      username: params.username,
      password: params.password,
      repassword: params.repassword,
      role: params.role
    }
  })
  return data
}

export async function updateUser(id, params) {
  const data = await request({
    url: '/user/update/' + id,
    method: 'put',
    data: {
      name: params.name,
      role: params.role
    }
  })
  return data
}
