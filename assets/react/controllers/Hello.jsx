import React from 'react';

export default function (props) {
    return (
        <div className="text-center text-lg font-semibold text-gray-700">
            Hello {props.fullName}
        </div>
    );
}