import React from 'react';
import ReactDOM from 'react-dom';

class Chat extends React.Component {
    render() {
        return (
            <div>
                Chat Component
            </div>
        )
    }
}

export default Chat;

if (document.getElementById('chat-container')) {
    ReactDOM.render(<Chat />, document.getElementById('chat-container'));
}
