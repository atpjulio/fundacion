import axios from 'axios';
import { useCallback } from 'react';

export default (otherParams) => {
  const loadOptions = useCallback(
    async (option) => {
      const params = {
        ...otherParams.params
      };

      const url = otherParams.url;
      const headers = {
        // 'Authorization': `Bearer ${token}`
      };
      const withCredentials = true;
      const response = await axios.get(url, {
        headers,
        params,
        withCredentials,
      });
      const asyncOptions = response.data?.result?.map((option) => ({
        value: String(option.id),
        label: option[otherParams.field],
      }));

      return asyncOptions;
    },[otherParams]
  );
  return loadOptions;
};
