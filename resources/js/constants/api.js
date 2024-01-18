const API_ROUTES = {
  SHOP_INFO: 'shop-info',
  PRODUCT: {
    GET: 'products',
    SHOW: 'product'
  },
  AUTHORIZE: 'oauth/redirect',
  FILE: {
    INDEX: 'files',
    REMOVE: (id) => `file/${id}`
  },
  INTEGRATION: 'integration-status'
};

export default API_ROUTES;
