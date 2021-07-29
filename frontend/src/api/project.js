import request from '@/utils/request'

export async function getProjects(query) {
  const data = await request({
    url: '/projects',
    method: 'get',
    params: query
  })
  return data
}

export async function createProject(params) {
  const data = await request({
    url: '/projects',
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
    url: '/projects/' + id,
    method: 'put',
    data: {
      project_name: params.project_name,
      description: params.description,
      date_start: params.date_start,
      date_end: params.date_end
    }
  })
  return data
}

export async function deleteProject(id) {
  const data = await request({
    url: '/projects/' + id,
    method: 'delete'
  })
  return data
}

export async function getMembers(projectId) {
  const data = await request({
    url: '/projects/' + projectId + '/members',
    method: 'get'
  })
  return data
}

export async function addMember(param, projectId) {
  const data = await request({
    url: '/projects/' + projectId + '/members',
    method: 'post',
    data: {
      user_id: param.user_id
    }
  })
  return data
}

export async function removeMember(projectId, memberId) {
  const data = await request({
    url: '/projects/' + projectId + '/members/' + memberId,
    method: 'delete'
  })
  return data
}
