import { createRequestActions, createRequestTypes } from 'utils/redux';
import {
  CREATE_ATTRACTION_ORDER,
  SET_ACTIVE_ATTRACTION_FILTER,
  CLEAN_ATTRACTION_ORDER_STATUS,
  TOGGLE_ORDER_CREATION_LOADER,
  GET_ATTRACTIONS,
  FETCH_ATTRACTIONS_OFFERS,
} from 'redux/constants/attractions';
import { createAction } from 'redux-actions';

export const setActiveAttractionFilter = createAction(
  SET_ACTIVE_ATTRACTION_FILTER,
  (payload) => ({ activeFilter: payload })
);

export const cleanAttractionOrderStatus = createAction(
  CLEAN_ATTRACTION_ORDER_STATUS
);

export const createAttrOrderTypes = createRequestTypes(CREATE_ATTRACTION_ORDER);
export const fetchAttractionsTypes = createRequestTypes(GET_ATTRACTIONS);

export const createAttrOrderActions = createRequestActions(
  'order',
  createAttrOrderTypes,
  'orderData'
);

export const fetchAttractionsActions = createRequestActions(
  'attractionsBrands',
  fetchAttractionsTypes
);

export const toggleCreateOrderLoader = createAction(
  TOGGLE_ORDER_CREATION_LOADER,
  (show) => ({ show })
);

export const fetchAttractionsOffersTypes = createRequestTypes(
  FETCH_ATTRACTIONS_OFFERS
);

export const fetchAttractionsOffersActions = createRequestActions(
  'offers',
  fetchAttractionsOffersTypes,
  'id'
);
