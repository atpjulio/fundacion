import axios from 'axios';

export default (props) => {
  const { url, params } = props;
  const getRecords = async () => {
    const headers = {
      // 'Authorization': `Bearer ${token}`
    };
    const withCredentials = true;

    const response = await axios.get(url, { headers, params, withCredentials });
    // const result = response.data.result;
    // const links = response.data.links;

    return response.data;
  };

  return getRecords;
};
