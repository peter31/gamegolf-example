import { applyMiddleware, createStore } from 'redux';
import createSagaMiddleware from 'redux-saga';
import { routerMiddleware } from 'connected-react-router';
import { composeWithDevTools } from 'redux-devtools-extension/logOnlyInProduction';
import logger from 'redux-logger';

import { getIsDebugEnv } from 'utils/config';

import { createRootReducer } from '../reducers';
import rootSaga from '../saga';

const sagaMiddleware = createSagaMiddleware();

export const configureStore = (history, initialState = {}) => {
  const middlewares = [routerMiddleware(history), sagaMiddleware];

  if (getIsDebugEnv()) {
    middlewares.push(logger);
  }

  const store = createStore(
    createRootReducer(history),
    initialState,
    composeWithDevTools(applyMiddleware(...middlewares))
  );

  sagaMiddleware.run(rootSaga);
  return store;
};
