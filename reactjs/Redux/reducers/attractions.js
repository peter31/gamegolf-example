import {
  SET_ACTIVE_ATTRACTION_FILTER,
  CLEAN_ATTRACTION_ORDER_STATUS,
  TOGGLE_ORDER_CREATION_LOADER,
} from '../constants/attractions';
import {
  createAttrOrderTypes,
  fetchAttractionsTypes,
  fetchAttractionsOffersTypes,
} from '../actions/attractions';

const initialState = {
  activeFilter: null,
  loading: false,
  error: '',
  success: false,
  order: null,
  attractionsBrands: [],
  offers: {},
  fetchingErorr: null,
  isBrandsLoaded: false,
  isOffersLoaded: false,
};

export const attractionsReducer = (state = initialState, action) => {
  switch (action.type) {
    case fetchAttractionsTypes.REQUEST: {
      return { ...state, error: '', loading: true };
    }
    case fetchAttractionsTypes.SUCCESS: {
      return {
        ...state,
        error: '',
        loading: false,
        ...action.payload,
        isBrandsLoaded: true,
      };
    }
    case fetchAttractionsTypes.FAILURE: {
      return {
        ...state,
        loading: false,
        error: action.payload.error?.data?.message,
        isBrandsLoaded: true,
      };
    }
    case SET_ACTIVE_ATTRACTION_FILTER: {
      return { ...state, ...action.payload };
    }
    case createAttrOrderTypes.REQUEST: {
      return {
        ...state,
        error: '',
        order: null,
      };
    }
    case createAttrOrderTypes.SUCCESS: {
      return {
        ...state,
        error: '',
        order: action.payload,
      };
    }
    case createAttrOrderTypes.FAILURE: {
      return {
        ...state,
        loading: false,
        error: action.payload.error?.data?.message,
        order: null,
      };
    }
    case CLEAN_ATTRACTION_ORDER_STATUS: {
      return {
        ...state,
        error: '',
        success: false,
        order: null,
      };
    }

    case TOGGLE_ORDER_CREATION_LOADER: {
      return {
        ...state,
        loading: action.payload.show,
      };
    }

    case fetchAttractionsOffersTypes.REQUEST:
      return { ...state, loading: true, fetchingErorr: null };
    case fetchAttractionsOffersTypes.SUCCESS:
      return {
        ...state,
        loading: false,
        ...action.payload,
        isOffersLoaded: true,
      };
    case fetchAttractionsOffersTypes.FAILURE:
      return {
        ...state,
        loading: false,
        fetchingErorr: action.payload.error,
        isOffersLoaded: true,
      };
    default:
      return state;
  }
};
