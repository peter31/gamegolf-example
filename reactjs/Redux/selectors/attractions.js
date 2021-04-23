import _get from 'lodash/get';
import _isEmpty from 'lodash/isEmpty';
import { getItemData } from 'redux/selectors/cms';
import { createSelector } from 'reselect';
import { ATTRACTION_TYPES_KEY } from 'constants/attractions';

export const getAttractions = (state) => _get(state, 'attraction', {});

export const getAttractionsBrands = createSelector(
  [getAttractions],
  ({ loading, attractionsBrands, isBrandsLoaded }) => ({
    isLoading: loading,
    isLoaded: isBrandsLoaded,
    brands: attractionsBrands,
  })
);

export const getIsAttractionLoading = createSelector(
  [getAttractions],
  ({ loading }) => loading
);

export const getAttractionsActiveFilter = createSelector(
  [getAttractions],
  (attractions) => attractions.activeFilter
);

export const getBrandsFilterOptions = createSelector(
  [(state) => getItemData(state, { key: ATTRACTION_TYPES_KEY, path: 'items' })],
  (brandsFilterOptions) =>
    brandsFilterOptions?.map(({ alias, title, ...rest }) => ({
      displayName: title,
      key: alias,
      ...rest,
    }))
);

export const getShouldAttractions = (selectedOfferId) =>
  createSelector([getAttractions], ({ offers, isOffersLoaded }) => {
    if (!_isEmpty(offers)) {
      return selectedOfferId !== offers.attraction_offers[0].brand_id;
    }
    return !isOffersLoaded;
  });

export const getOrderCreationData = createSelector(
  [getAttractions],
  (attractions) => ({
    loading: attractions.loading,
    order: attractions.order,
    error: attractions.error,
  })
);

export const getAttractionsOffers = createSelector(
  [getAttractions],
  ({ offers: { attraction_offers, brand_name }, loading }) => ({
    loading,
    attraction_offers,
    brand_name,
  })
);
