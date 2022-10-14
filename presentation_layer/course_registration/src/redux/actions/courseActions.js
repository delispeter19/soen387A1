import axios from 'axios';
import { createMessage, returnErrors } from './messageActions';
import { apiconfig } from './authActions';
import { GET_COURSES, ADD_COURSE, DELETE_COURSE } from './types';

// GET STUDENTS API CALL
export const getCourses = () => (dispatch, getState) => {

    const config = apiconfig(getState);

    axios.get('http://localhost/a1/api/course/get.php', config)
        .then(res => {
            dispatch({
                type: GET_COURSES,
                payload: res.data
            });
        }).catch(err => dispatch(returnErrors(err.response.data, err.response.status)));
}

// POST STUDENT API CALL
export const addCourse = (course) => (dispatch, getState) => {

    const config = apiconfig(getState);

    axios.post('http://localhost/a1/api/course/post.php', course, config)
        .then(res => {
            dispatch(createMessage({
                createCourse: 'Course Created'
            }));
            dispatch({
                type: ADD_COURSE,
                payload: res.data
            });
        }).catch(err => dispatch(returnErrors(err.response.data, err.response.status)));
}

// DELETE STUDENT API CALL
export const deleteCourse = (code) => (dispatch, getState) => {

    const config = apiconfig(getState);

    axios.delete(`http://localhost/a1/api/course/delete.php?code=${code}`, config)
        .then(res => {
            dispatch(createMessage({
                deleteCourse: 'Course Removed'
            }));
            dispatch({
                type: DELETE_COURSE,
                payload: res.data
            });
        }).catch(err => console.log(err));
}


// UPDATE PATIENT PRIORITY API CALL
// export const togglePriority = (id) => (dispatch, getState) => {

//     const config = tokenConfig(getState);

//     axios.put(`http://localhost:8000/api/toggle/priority/${id}/`, null, config)
//         .then(res => {
//             axios.get('http://localhost:8000/api/patients/', config)
//             .then(res => {
//                 dispatch({
//                     type: GET_PATIENTS,
//                     payload: res.data
//                 });
//             }).catch(err => dispatch(returnErrors(err.response.data, err.response.status)));
//         }).catch(err => {console.log(err); dispatch(returnErrors(err.response.data, err.response.status))});
// }
