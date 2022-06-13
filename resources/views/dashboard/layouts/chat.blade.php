@php
    $img = 'https://cdn.icon-icons.com/icons2/2643/PNG/512/male_boy_person_people_avatar_icon_159358.png';
@endphp

<div class="main-message">
    <div class="contact-profile">
        <p class="my-2"><strong>Thảo luận/Ghi chú</strong></p>
        <div class="social-media">
            <i class="fa fa-facebook" aria-hidden="true"></i>
            <i class="fa fa-twitter" aria-hidden="true"></i>
            <i class="fa fa-instagram" aria-hidden="true"></i>
        </div>
    </div>

    <div class="content">
        <ul id="message-content" class="my-3 px-2" style="list-style: none;">
            @foreach ($messages as $message)
                @php
                    $sender = $message->sender;
                @endphp

                @if ($sender->is(user()))
                <li class="message sent">
                    <img class="avatar" src="{{ $img }}" alt="" />
                    <div class="message-container">
                        <p class="message-sender">{{ $sender->name ?? 'unknow' }}</p>
                        <p class="message-content">{{ $message->content }}</p>
                    </div>
                </li>
                @else
                <li class="message replies">
                    <img class="avatar" src="{{ $img }}" alt="" />
                    <div class="message-container">
                        <p class="message-sender">{{ $sender->name ?? 'unknow' }}</p>
                        <p class="message-content">{{ $message->content }}</p>
                    </div>
                </li>
                @endif
            @endforeach
        </ul>
    </div>

    <div class="message-input">
        <input id="message-input" class="form-control mr-2" type="text" placeholder="Nhắn gì đó..." />
        {{-- <i class="fa fa-paperclip attachment" aria-hidden="true"></i> --}}
        <button id="message-send-btn" class="btn btn-primary">Gửi</button>
    </div>
</div>

@push('script')
<script src="{{ asset('assets/js/app.js') }}"></script>

<script>
!function () {
    const ids = [];

    const input = $('#message-input')
    const messages = $('#message-content')
    const topic = {
        _token: '{{ csrf_token() }}',
        topic_id: '{{ $topic->id }}',
        topic_type: '{{ addslashes(get_class($topic)) }}'
    }

    const scroll = () => {
        const element = $('.main-message > .content')

        element.scrollTop(element.get(0).scrollHeight)
    }

    const send = () => {
        const content = input.val()
        input.val('')

        fetch('{{ route("manager.message.api.store") }}', {
            method: 'POST',
            headers: { 'content-type': 'application/json' },
            body: JSON.stringify({ ...topic, content })
        }).then(res => {
            if (res.ok) return res.json()
        }).then(message => {
            ids.push(message.id)

            messages.append(`
                <li class="message sent">
                    <img class="avatar" src="{{ $img }}" alt="" />
                    <div class="message-container">
                        <p class="message-sender">{{ user()->name }}</p>
                        <p class="message-content">${message.content}</p>
                    </div>
                </li>
            `);

            scroll()
        }).catch(() => {
            Snackbar.show({
                text: 'Danger',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
                text: "Tin nhắn không gửi được, xin hãy thử lại sau vài phút",
                pos: 'bottom-right',
                duration: 5000,
                showAction: false
            })
        })
    }

    $(document).ready(scroll)
    $('#message-send-btn').click(send)
    input.on('keypress', e => { e.which === 13 && send() })


    const channel = Echo.private('customer.{{ $topic->id }}');

    channel.listen('message:created', ({ content, sender }) => {


        const user = '{{ Auth::id() }}'

        if (ids.find(id => content.id === id)) {
            return
        }

        messages.append(`
            <li class="message ${ user == sender.id ? 'sent' : 'replies' }">
                <img class="avatar" src="{{ $img }}" alt="" />
                <div class="message-container">
                    <p class="message-sender">${ sender.name }</p>
                    <p class="message-content">${content.content}</p>
                </div>
            </li>
        `);

        scroll()
    });
}()
</script>
@endpush

@push('style')
<style>
.main-message {
    position: relative;
    background-color: #e6eaea;
}

.main-message .contact-profile {
    display: flex;
    padding: .5rem;
    background-color: #f5f5f5;
}

.main-message .content {
    overflow-y: scroll;
    max-height: 300px;
}

.main-message .content .message {
    color: #f5f5f5;
    display: flex;
}

.message .message-content {
    padding: 10px 15px;
    border-radius: .5rem;
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
}

.message .message-container {
    display: flex;
    flex-direction: column;
    max-width: 60%
}

.message .message-sender {
    display: inline-block;
    margin-bottom: .0rem;
}

.replies .message-content {
    background: #435f7a;
    color: #f5f5f5;
}

.sent .message-content {
    background: #f5f5f5;
}

.sent .message-sender {
    text-align: right;
}

.main-message .sent {
    flex-direction: row-reverse;
}

.main-message .avatar {
    max-width: 30px;
    max-height: 30px;
    margin-right: .75rem;
    margin-left: .75rem;
}

.main-message .message-input {
    background-color: #f5f5f5;
    display: flex;
    padding: .5rem;
}

.main-message .message-input .text {
    width: 100%;
}
</style>
@endpush
