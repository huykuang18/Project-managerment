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

export async function deleteUser(id) {
  const data = await request({
    url: '/user/delete/' + id,
    method: 'delete'
  })
  return data
}

export async function createUser(data) {
  const param = await request({
    url: '/register',
    method: 'post',
    data: {
      name: data.name,
      username: data.username,
      password: data.password,
      repassword: data.repassword,
      role: data.role
    }
  })
  return param
}

export async function updateUser(id, data) {
  const param = await request({
    url: '/user/update/' + id,
    method: 'put',
    data: {
      name: data.name,
      role: data.role
    }
  })
  return param
}
