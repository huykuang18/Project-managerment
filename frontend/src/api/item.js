import request from '@/utils/request'

export async function getItems(query) {
  const data = await request({
    url: '/items',
    method: 'get',
    params: query
  })
  return data
}

export async function createItem(proId, params) {
  const data = await request({
    url: '/items',
    method: 'post',
    data: {
      name: params.name,
      order: params.order,
      project_id: proId,
      parent_id: params.parent_id
    }
  })
  return data
}

export async function updateItem(id, proId, params) {
  const data = await request({
    url: '/items/' + id,
    method: 'put',
    data: {
      name: params.name,
      order: params.order,
      project_id: proId,
      parent_id: params.parent_id
    }
  })
  return data
}

export async function deleteItem(id) {
  const data = await request({
    url: '/items/' + id,
    method: 'delete'
  })
  return data
}
