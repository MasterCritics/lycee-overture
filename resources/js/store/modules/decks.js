import { listDecks } from '../../api/endpoints/decks';

export default {
  namespaced: true,
  state: {
    listLoading: false,
    list: null,
  },
  mutations: {
    DECKS_LOADING(state) {
      state.listLoading = true;
    },
    DECKS_LOADED(state, cardsResponse) {
      state.list = cardsResponse;
      state.listLoading = false;
    },
    DECKS_LOADING_FAILED(state) {
      state.listLoading = false;
    },
  },
  actions: {
    async listDecks({ commit }) {
      commit('DECKS_LOADING');
      try {
        const cards = await listDecks();
        commit('DECKS_LOADED', cards);
      } catch (e) {
        commit('DECKS_LOADING_FAILED');
        throw e;
      }
    },
  },
};
