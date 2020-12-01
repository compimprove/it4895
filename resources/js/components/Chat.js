
import { toNumber } from 'lodash';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { url } from '../helper';

class Chat extends React.Component {
    constructor(props) {
        super(props)

        this.otherId = this.props.otherId;
    }


    sendMessage(message) {
        this.props.sendMessage(message);
    }

    render() {
        return (
            <div>
                <div className="row">
                    <div className="col-md-8 col-md-offset-2">
                        <div className="panel panel-default">
                            <div className="panel-heading">Chats</div>
                            <div className="panel-body">
                                {this.props.messages.map(message =>
                                    <Message
                                        key={message.id}
                                        message={message}
                                        isOtherMessage={message.user_b_id === this.props.otherId}
                                    />)
                                }
                            </div>
                            <div className="panel-footer">
                                <SendMessageForm
                                    sendMessage={this.sendMessage.bind(this)} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

function Message({ message, isOtherMessage }) {
    return (
        <p>
            <label style={{ textAlign: isOtherMessage ? "right" : "left", width: "100%" }}>
                {message.content}
            </label>
        </p>
    )
}

class SendMessageForm extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            text: ''
        }
    }

    sendMessage() {
        this.props.sendMessage(this.state.text);
        this.setState({ text: '' });
    }

    render() {
        return (
            <div>
                <input
                    value={this.state.text}
                    onChange={(e) => {
                        this.setState({
                            text: e.target.value
                        })
                    }} className="form-control" type="text" placeholder="Message" />
                <input type="submit" onClick={this.sendMessage.bind(this)} className="btn btn-primary" />
            </div>
        );
    }
}


class ChatContainer extends Component {
    constructor(props) {
        super(props);
        let token = document.querySelector('meta[name="api-token"]').getAttribute("token")
        axios.defaults.headers.common = { 'Authorization': `Bearer ${token}` }
        this.friendsId = [1, 2, 3, 4]
        this.userId = toNumber(document.querySelector('meta[name="api-token"]').getAttribute("userid"));
        this.state = {
            otherId: '',
            messages: []
        }
    }

    componentDidMount() {
        let _this = this;
        axios.get(url("/it4895/messages/" + _this.state.otherId))
            .then(response => {
                console.log(response.data);
                _this.setState({
                    messages: response.data
                })
            })
        Echo.private('chat')
            .listen('ChatEvent', (e) => {
                console.log(e.chat);
                let messages = this.state.messages.concat(e.chat);
                this.setState({
                    messages: messages
                })
            });
    }

    chooseFriend(id) {
        let _this = this;
        axios.get(url("/it4895/messages/" + id))
            .then(response => {
                console.log(response.data);
                _this.setState({
                    messages: response.data,
                    otherId: id
                })
            })
    }

    sendMessage(message) {
        console.log("send message: " + message);
        axios.post(url("/it4895/messages/") + `${this.state.otherId}?content=${message}`)
            .then(response => {
                console.log(response.data);
            })
    }

    render() {
        return (
            <>
                {this.friendsId.map(value => {
                    if (value === this.userId) return
                    let onClick = this.chooseFriend.bind(this, value)
                    if (this.state.otherId === value) {
                        return (
                            <button onClick={onClick} className="btn btn-primary">
                                UserId {value}
                            </button>)
                    } else {
                        return (
                            <button onClick={onClick} className="btn btn-outline-primary">
                                UserId {value}
                            </button>)
                    }
                })}
                <Chat
                    otherId={this.state.otherId}
                    messages={this.state.messages}
                    sendMessage={this.sendMessage.bind(this)} />
            </>
        );
    }
}

export default ChatContainer;


if (document.getElementById('chat-container')) {
    ReactDOM.render(<ChatContainer />, document.getElementById('chat-container'));
}
