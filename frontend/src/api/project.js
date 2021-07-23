import request from '@/utils/request'

export async function getProjects(query) {
  const data = await request({
    url: '/projects',
    method: 'get',
    params: query
  })
  return data
}

export async function getMembers(project_id) {
  const data = await request({
    url: '/project/members',
    method: 'get',
    params: { project_id }
  })
  return data
}

export async function createProject(params) {
  const data = await request({
    url: '/project/create',
    method: 'post',
    data: {
      project_name: params.project_name,
      customer: params.customer,
      description: params.description,
      date_start: params.date_start,
      date_end: params.date_end
    }
  })
  return data
}

export async function updateProject(id, params) {
  const data = await request({
    url: '/project/update',
    method: 'put',
    data: {
      project_name: params.project_name,
      description: params.description,
      date_start: params.date_start,
      date_end: params.date_end
    },
    params: { id }
  })
  return data
}

export async function deleteProject(id) {
  const data = await request({
    url: '/project/delete/' + id,
    method: 'delete'
  })
  return data
}

export async function addMember(param, id) {
  const data = await request({
    url: 'project/member/add',
    method: 'post',
    data: {
      user_id: param.user_id
    },
    params: { id }
  })
  return data
}

export async function removeMember(member_id, id) {
  const data = await request({
    url: '/project/member/delete/' + member_id,
    method: 'delete',
    params: { id }
  })
  return data
}
