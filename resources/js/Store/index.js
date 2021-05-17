import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        entries: [],
        total: 0
    },
    mutations: {
        SET_BALANCE(state, payload){
            state.total = payload;
        },

        SET_ENTRIES(state, entries){
            state.entries = entries
        },

        ADD_ENTRY(state, entry){
            state.entries = [...state.entries, entry]
        },

        UPDATE_ENTRY(state, payload){
            let entries    = [...state.entries];
            let index      = entries.findIndex(entry => entry.id === payload.id)
            entries[index] = payload;

            state.entries = entries;
        },

        DELETE_ENTRY(state, id){
            state.entries = [...state.entries].filter(entry => {
                return entry.id !== id
            })
        },

        UPDATE_BALANCE(state, amount){
            state.total = state.total + amount
        }
    },

    getters: {
        entriesGroup(state) {
            let data = {};

            if (state.entries.length){

                let not_ordered_data = _.groupBy(state.entries, (entry) => {
                    return $dayjs.utc(entry.date).format('YYYY-MM-DD')
                });

                Object.keys(not_ordered_data)
                    .sort((a,b) => ($dayjs(a).isBefore($dayjs(b)) ? 1 : -1))
                    .map(key => {
                        data[key] = not_ordered_data[key]
                    });
            }

            return data;
        },

        entriesGroupKeys(state){
            let keys = [];

            if (state.entries.length){
                let not_ordered_data = _.groupBy(state.entries, (entry) => {
                    return $dayjs.utc(entry.date).format('YYYY-MM-DD')
                });

                keys = Object.keys(not_ordered_data)
                    .sort((a,b) => ($dayjs(a).isBefore($dayjs(b)) ? 1 : -1));
            }

            return keys;
        }
    },

    actions: {
        getBalance({ commit }){
            return axios.get(`/balance`).then(r => {
                commit('setBalance', r.data);
                return r.data;
            });
        },

    }
})
