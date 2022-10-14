import axios from 'axios';
import { createMessage, returnErrors } from './messageActions';
import { apiconfig } from './authActions';
import { GET_STUDENTS, ADD_STUDENT, DELETE_STUDENT } from './types';

// GET STUDENTS API CALL
export const getStudents = () => (dispatch, getState) => {

    const config = apiconfig(getState);

    axios.get('http://localhost/a1/api/student/get.php', config)
        .then(res => {
            dispatch({
                type: GET_STUDENTS,
                payload: res.data
            });
        }).catch(err => dispatch(returnErrors(err.response.data, err.response.status)));
}

// POST STUDENT API CALL
export const addStudent = (student) => (dispatch, getState) => {

    const config = apiconfig(getState);

    axios.post('http://localhost/a1/api/student/post.php', student, config)
        .then(res => {
            dispatch(createMessage({
                addStudent: 'Student Added'
            }));
            dispatch({
                type: ADD_STUDENT,
                payload: res.data
            });
        }).catch(err => dispatch(returnErrors(err.response.data, err.response.status)));
}

// DELETE STUDENT API CALL
export const deleteStudent = (id) => (dispatch, getState) => {

    const config = apiconfig(getState);

    axios.delete(`http://localhost/a1/api/student/delete.php?id=${id}`, config)
        .then(res => {
            dispatch(createMessage({
                deleteStudent: 'Student Deleted'
            }));
            dispatch({
                type: DELETE_STUDENT,
                payload: res.data
            });
        }).catch(err => dispatch(returnErrors(err.response.data, err.response.status)));
}
