import request from '@/utils/request'

export async function getIssues(query) {
  const data = await request({
    url: '/issues',
    method: 'get',
    params: query
  })
  return data
}

export async function createIssue(params) {
  const data = await request({
    url: '/issues',
    method: 'post',
    data: {
      issue_name: params.issue_name,
      user_id: params.user_id,
      priority: params.priority,
      item_id: params.item_id,
      deadline: params.deadline
    }
  })
  return data
}

export async function updateIssue(id, params) {
  const data = await request({
    url: '/issues/' + id,
    method: 'put',
    data: {
      issue_name: params.issue_name,
      user_id: params.user_id,
      priority: params.priority,
      item_id: params.item_id,
      deadline: params.deadline,
      status: params.status
    }
  })
  return data
}

export async function deleteIssue(id) {
  const data = await request({
    url: '/issues/' + id,
    method: 'delete'
  })
  return data
}

export async function memberOptions(project_id) {
  const data = await request({
    url: '/member/options',
    method: 'get',
    params: { project_id }
  })
  return data
}
