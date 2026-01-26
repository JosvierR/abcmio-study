import react from 'react';
import ReactDOM from 'react-dom';
import React from "react";
import Example from "../components/Example";

export const ChatApp = () => {
    return (
        <>
            <h1>Chat App</h1>
        </>
    );
}

if (document.getElementById('ReactApp')) {
    ReactDOM.render(<ChatApp />, document.getElementById('ReactApp'));
}