import React, { useCallback, useEffect, useState } from "react";
import isEqual from "lodash/isEqual";
import { useFirestore } from "react-redux-firebase";
import { useSelector } from "react-redux";

import { emptyJob, JOB_COLLECTION, JOB_TYPES } from "../../constants";
import { AppState } from "../../../../redux/store";
import { IJob } from "../../../../interfaces/jobs.interface";

interface Props {
  id: string;
  setError: React.Dispatch<React.SetStateAction<string>>;
  setLoading: React.Dispatch<React.SetStateAction<boolean>>;
}

interface UseCreateJobReturn {
  job: IJob;
  saveDataToDraft: (job: IJob, saveJob?: boolean) => Promise<void>;
  updateData: (value: any, key: string, saveToDB?: boolean) => void;
  isDataSaved: boolean;
  isJobDescriptionValid: boolean;
  isTalentInfoValid: boolean;
}

export const useCreateJob = ({
  id,
  setError,
  setLoading,
}: Props): UseCreateJobReturn => {
  const [job, setJob] = useState<IJob>(emptyJob);
  const firestore = useFirestore();

  const dbJob = useSelector(
    ({ firestore: { data } }: AppState) => data.jobs && data.jobs[id]
  );

  const isDataSaved = isEqual(job, dbJob);

  useEffect(() => {
    if (dbJob && id) {
      setJob(dbJob);
    }
  }, [dbJob, id]);

  const updateData = async (value: any, key: string, saveToDB = false) => {
    const newJob = { ...job, [key]: value };
    setJob(newJob);
    if (saveToDB) {
      await saveDataToDraft(newJob);
    }
  };

  const {
    title,
    whyLooking,
    typeOfContract,
    typeOfEmployment,
    placeOfWork,
    displayPosition,
    experience,
    languages,
    status,
  } = job;

  const isJobDescriptionValid =
    !!title &&
    !!whyLooking &&
    !!typeOfEmployment?.length &&
    !!typeOfContract?.length &&
    !!placeOfWork &&
    !!displayPosition;

  const isTalentInfoValid = !!(
    experience &&
    experience.supertalents?.length &&
    experience.seniority &&
    languages?.length
  );

  const autoSaveJob = status === JOB_TYPES.DRAFT;

  const saveDataToDraft = useCallback(
    async (newJob: IJob, saveJob = autoSaveJob) => {
      if (id) {
        if (newJob && !isEqual(newJob, dbJob) && saveJob) {
          try {
            setLoading(true);
            await firestore
              .collection(JOB_COLLECTION)
              .doc(id)
              .update({
                ...newJob,
                updatedAt: firestore.FieldValue.serverTimestamp(),
              });
          } catch (e) {
            console.log(e);
            setError(e.message);
          } finally {
            setLoading(false);
          }
        }
      }
    },
    [autoSaveJob, dbJob, firestore, id, setError, setLoading]
  );

  return {
    job,
    saveDataToDraft,
    updateData,
    isDataSaved,
    isJobDescriptionValid,
    isTalentInfoValid,
  };
};
