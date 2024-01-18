import API_ROUTES from '../constants/api';
import { useAppMutation, useAppQuery } from '../hooks/useAppQuery';

export const useProductsQuery = (params, reactQueryOptions = {}) => {
  return useAppQuery({
    params,
    url: API_ROUTES.PRODUCT.GET,
    reactQueryOptions
  });
};

export const useShopInfoQuery = (reactQueryOptions = {}) => {
  return useAppQuery({
    url: API_ROUTES.SHOP_INFO,
    reactQueryOptions
  });
};

export const useProductQuery = (
  productId,
  reactQueryOptions = {}
) => {
  return useAppQuery({
    url: `${API_ROUTES.PRODUCT.SHOW}/${productId}`,
    reactQueryOptions
  });
};

export const useAuthorizeQuery = (reactQueryOptions = {}) => {
  return useAppQuery({
    url: `${API_ROUTES.AUTHORIZE}`,
    reactQueryOptions
  });
};

export const useTestQuery = (reactQueryOptions = {}) => {
  return useAppQuery({
    url: `test`,
    reactQueryOptions
  });
};

export const useFilesQuery = (reactQueryOptions = {}) => {
  return useAppQuery({
    url: `${API_ROUTES.FILE.INDEX}`,
    reactQueryOptions
  });
};

export const useDeleteFilesMutation = (
  id,
  reactQueryOptions = {}
) => {
  return useAppMutation({
    method: 'DELETE',
    url: API_ROUTES.FILE.REMOVE(id),
    reactQueryOptions
  });
};

export const useIntegrationStatusQuery = (reactQueryOptions = {}) => {
  return useAppQuery({
    url: `${API_ROUTES.INTEGRATION}`,
    reactQueryOptions
  });
};
