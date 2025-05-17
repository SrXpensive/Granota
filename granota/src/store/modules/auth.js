const state = () => ({
    token: localStorage.getItem('token') || null,
    user: null
});

const mutations = {
    setToken(state, token){
        state.token = token;
    },
    setUser(state,user){
        state.user = user;
    },
    logout(state){
        state.token = null;
        state.user = null;
    }
}

const actions = {
    async login({commit},{email,password}){
        const response = await fetch('http://localhost:8000/api/login',{
            method: 'POST',
            headers: {
                'Content-type':'application/json'
            },
            body: JSON.stringify({email, password})
        });

        const data = await response.json();

        if(!response.ok){
            throw new Error(data.error || 'Error en el login');
        }

        localStorage.setItem('token', data.token);
        commit('setToken', data.token);
    },

    logout({commit}){
        localStorage.removeItem('token');
        commit('logout');
    }
};

const getters = {
    isAuthenticated: (state) => !!state.token,
    getUser: (state) => state.user,
    getToken: (state) => state.token
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}