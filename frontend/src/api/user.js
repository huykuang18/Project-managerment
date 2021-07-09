import request from '@/utils/request'

export async function login(params) {
    const data = await request({
        url: '/login',
        method: 'post',
        params
    });
    return data;
}

export async function getInfo(token) {
    const data = await request({
        url: '/user-info',
        method: 'get',
        params: { token }
    });
    return data;
}

export async function logout(token) {
    const data = await request({
        url: '/logout',
        method: 'get',
        params: {token}
    });
    return data;
}