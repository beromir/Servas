import {http} from '@inertiajs/svelte';

function parse(body) {
    try {
        return JSON.parse(body);
    } catch {
        return body;
    }
}

async function request(method, url, data) {
    try {
        const response = await http.getClient().request({method, url, data, headers: {Accept: 'application/json'}});

        return parse(response.data);
    } catch (error) {
        if (error.response) {
            error.data = parse(error.response.data);
        }

        throw error;
    }
}

export const getJson = url => request('get', url);

export const postJson = (url, data) => request('post', url, data);
