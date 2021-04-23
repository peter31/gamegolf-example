import {
  call,
  all,
  takeEvery,
  put,
  debounce,
  takeLatest,
} from 'redux-saga/effects';
import {
  createOrder,
  fetchAttractions,
  fetchAttractionOffers,
} from 'services/api/attractions';

import {
  createAttrOrderActions,
  createAttrOrderTypes,
  toggleCreateOrderLoader,
  fetchAttractionsTypes,
  fetchAttractionsActions,
  fetchAttractionsOffersTypes,
  fetchAttractionsOffersActions,
} from '../actions/attractions';
import { TOGGLE_ORDER_CREATION_LOADER } from '../constants/attractions';

function* createAttrOrder(action) {
  try {
    const { orderData } = action.payload;
    const request = {
      product_id: 'md-attraction-experience',
      qty: 1,
      data: { offer_name: orderData.offer_name },
    };
    yield put(toggleCreateOrderLoader(true));
    const {
      data: { order },
    } = yield call(createOrder, request);
    yield put(createAttrOrderActions.success(order));
  } catch (err) {
    yield put(createAttrOrderActions.failure(err));
  }
}

function* onFetchAttractions() {
  try {
    const {
      data: { brands },
    } = yield call(fetchAttractions);
    yield put(fetchAttractionsActions.success(brands));
  } catch (error) {
    yield put(createAttrOrderActions.failure(error));
  }
}

function* fetchAttrOffers({ payload: { id } }) {
  try {
    const {
      data: { attraction_offers, brand_name },
    } = yield call(fetchAttractionOffers, id);
    yield put(
      fetchAttractionsOffersActions.success({ attraction_offers, brand_name })
    );
  } catch (error) {
    yield put(fetchAttractionsOffersActions.failure(error?.data?.message));
  }
}

export function* attractionSaga() {
  yield all([
    takeEvery(createAttrOrderTypes.REQUEST, createAttrOrder),
    takeLatest(fetchAttractionsTypes.REQUEST, onFetchAttractions),
    takeLatest(fetchAttractionsOffersTypes.REQUEST, fetchAttrOffers),
  ]);
  // eslint-disable-next-line func-names
  yield debounce(5000, TOGGLE_ORDER_CREATION_LOADER, function* () {
    yield put(toggleCreateOrderLoader(false));
  });
}
