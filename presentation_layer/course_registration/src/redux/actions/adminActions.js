import axios from 'axios';
import { createMessage, returnErrors } from './messageActions';
import { apiconfig } from './authActions';
import { GET_ADMINS, ADD_ADMIN, DELETE_ADMIN } from './types';

// GET STUDENTS API CALL
export const getAdmins = () => (dispatch, getState) => {

    const config = apiconfig(getState);

    axios.get('http://localhost/a1/api/administrator/get.php', config)
        .then(res => {
            dispatch({
                type: GET_ADMINS,
                payload: res.data
            });
        }).catch(err => dispatch(returnErrors(err.response.data, err.response.status)));
}

// POST STUDENT API CALL
export const addAdmin = (student) => (dispatch, getState) => {

    const config = apiconfig(getState);

    axios.post('http://localhost/a1/api/administrator/post.php', student, config)
        .then(res => {
            dispatch(createMessage({
                addAdmin: 'Admin Added'
            }));
            dispatch({
                type: ADD_ADMIN,
                payload: res.data
            });
        }).catch(err => dispatch(returnErrors(err.response.data, err.response.status)));
}

// DELETE STUDENT API CALL
export const deleteAdmin = (id) => (dispatch, getState) => {

    const config = apiconfig(getState);

    axios.delete(`http://localhost/a1/api/administrator/delete.php?id=${id}`, config)
        .then(res => {
            dispatch(createMessage({
                deleteAdmin: 'Admin Deleted'
            }));
            dispatch({
                type: DELETE_ADMIN,
                payload: id
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
