import { ref } from 'vue';
import axios from 'axios';

export function useStackOverflowSearch() {
  const results = ref([]);
  const loading = ref(false);
  const error = ref('');

  const search = async (query) => {
    loading.value = true;
    error.value = '';
    results.value = [];
    try {
      const url = `https://api.stackexchange.com/2.3/search/advanced?order=desc&sort=relevance&q=${encodeURIComponent(query)}&site=stackoverflow`;
      const res = await axios.get(url);
      results.value = res.data.items || [];
    } catch (e) {
      error.value = 'No se pudo obtener resultados de StackOverflow.';
    } finally {
      loading.value = false;
    }
  };

  return { results, loading, error, search };
} 