import * as React from 'react'
import { useDispatch, useSelector } from 'react-redux'
import {useEffect} from "react";
import {fetchAttributes} from "../redux/api/attribute";
import {AttributeList} from "./AttributeList";


export function App({id}) {
    const dispatch = useDispatch()
    const { status, error } = useSelector((state) => state.attributes)

    useEffect(() => {
        dispatch(fetchAttributes(id))
    }, [dispatch])
    return (
        <>
            <p>Hello world  {id}</p>
            {status === 'loading' && <p>Loading...</p>}
            {status === 'resolved' && <AttributeList />}
            {error && <h2>An error occurred: {error}</h2>}
        </>
    );
}