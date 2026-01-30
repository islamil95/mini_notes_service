import axios from 'axios';

const BASE = '/api/notes';

export const notesApi = {
  list() {
    return axios.get(BASE);
  },
  get(id) {
    return axios.get(`${BASE}/${id}`);
  },
  create(data) {
    return axios.post(BASE, data);
  },
  update(id, data) {
    return axios.put(`${BASE}/${id}`, data);
  },
  delete(id) {
    return axios.delete(`${BASE}/${id}`);
  },
};
