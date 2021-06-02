@php
  $img = 'https://cdn.icon-icons.com/icons2/2643/PNG/512/male_boy_person_people_avatar_icon_159358.png';

  try {
    $messages = \App\Models\Message::whereTopic(user())->with(['sender', 'topic'])->latest()->limit(20)->get();
  } catch (\Throwable $th) {
    $messages = collect();
  }
@endphp

<div class="boxchat boxchat-left">
    <div class="boxchat-header">
      <div class="boxchat-count boxchat-hidden"></div>
      <span class="boxchat-title shadow">Hỗ trợ</span>
    </div>
    <div class="boxchat-iframe shadow">

        <div class="main-message">
            <div class="contact-profile shadow-sm">
                <div>
                  <p class="my-2"><strong>Hỗ trợ khách hàng</strong></p>
                </div>
                <div id="boxchat-x" class="mx-2" style="cursor: pointer;">
                  ✕
                </div>
            </div>
        
            <div class="content">
                <ul id="message-content" class="my-4 px-2" style="list-style: none;">
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
        
            <div class="message-input shadow-sm">
                <input id="message-input" class="form-control mr-2" type="text" placeholder="Nhắn gì đó..." />
                {{-- <i class="fa fa-paperclip attachment" aria-hidden="true"></i> --}}
                <button id="message-send-btn" class="btn btn-primary">Gửi</button>
            </div>
        </div>
        
    </div>
</div>

@push('script')
@php
  $topic = user();
@endphp

<script>
$(document).ready(function () {
  const ids = [];

  const input = $("#message-input");
  const messages = $("#message-content");
  const topic = {
    _token: "{{ csrf_token() }}",
  };

  const scroll = () => {
    const element = $(".main-message > .content");

    element.scrollTop(element.get(0).scrollHeight);
  };

  const send = () => {
    const content = input.val();
    input.val("");

    fetch('{{ route("message.api.store") }}', {
      method: "POST",
      headers: {
        "content-type": "application/json",
      },
      body: JSON.stringify({
        ...topic,
        content,
      }),
    })
      .then((res) => {
        if (res.ok) return res.json();
      })
      .then((message) => {
        ids.push(message.id);

        messages.append(`
              <li class="message sent">
                  <img class="avatar" src="{{ $img }}" alt="" />
                  <div class="message-container">
                      <p class="message-sender">{{ user()->name }}</p>
                      <p class="message-content">${message.content}</p>
                  </div>
              </li>
          `);

        scroll();
      })
      .catch(() => {
        Snackbar.show({
          text: "Danger",
          actionTextColor: "#fff",
          backgroundColor: "#e7515a",
          text: "Tin nhắn không gửi được, xin hãy thử lại sau vài phút",
          pos: "bottom-right",
          duration: 5000,
          showAction: false,
        });

        input.val(content);
      });
  };

  $(document).ready(scroll);
  $("#message-send-btn").click(send);
  input.on("keypress", (e) => {
    e.which === 13 && send();
  });

  const channel = Echo.private("customer.{{ $topic->id }}");

  channel.listen("message:created", ({ content, sender }) => {
    const user = "{{ Auth::id() }}";

    if (ids.find((id) => content.id === id)) {
      return;
    }

    messages.append(`
        <li class="message ${user == sender.id ? "sent" : "replies"}">
            <img class="avatar" src="{{ $img }}" alt="" />
            <div class="message-container">
                <p class="message-sender">${sender.name}</p>
                <p class="message-content">${content.content}</p>
            </div>
        </li>
    `);

    scroll();
  });
});
</script>
@endpush

@push('script')
<script>
!function () {
  const boxchat = document.querySelector('.boxchat')

  const toggle = () => {
    boxchat.classList.toggle('boxchat-opened')
  }

  const registerToggleOpenTitle = (origin) => {
    const title = document.querySelector('.boxchat-title')
    const close = document.querySelector('#boxchat-x')

    title.addEventListener('click', toggle)
    title.addEventListener('touchstart', toggle)
    close.addEventListener('click', toggle)
    close.addEventListener('touchstart', toggle)

  }

  window.addEventListener('load', registerToggleOpenTitle)
}()
</script>
@endpush


<style>
    .boxchat {
        font-family: Nunito;
        opacity: 1 !important;
        top: initial !important;
        left: initial !important;
        border: 0;
        bottom: -436px;
        position: fixed;
        width: 325px;
        height: 470px;
        z-index: 10;
        transition: all .4s;
        -webkit-transition: all .4s;
        border-radius: 5px 5px 0 0;
      }
      .boxchat-header {
        display: flex;
        justify-content: flex-end;
      }
      .boxchat-title {
        cursor: pointer;
        background: #01133c url("${messageImg}") 10px center no-repeat;
        background-size: 30px;
        text-transform: uppercase;
        color:white;
        padding: 5px 15px 5px 15px;
        background-color: #44e3a9;
        font-size: large;
        border-top-right-radius: .25rem;
        border-top-left-radius: .25rem;
        font-family: 'Nunito';
      }
      .boxchat-header .boxchat-count {
        position: absolute;
        border-radius: 50%;
        background: #fb4739;
        width: 23px;
        height: 23px;
        text-align: center;
        color: wheat;
        font-size: 16px;
        top: -10px;
        right: -10px;
        font-weight: 600;
      }
      .boxchat-left .boxchat-header {
        justify-content: start;
      }
      .boxchat-left {
        left: 50px !important;
      }
      .boxchat-right {
        right: 50px !important;
      }
      .boxchat-opened {
        bottom: 20px;
      }
      .boxchat-hidden {
        display: none !important;
      }
      .boxchat-iframe {
        position: absolute;
        border: 1px solid;
        left: 0;
        width: 100%;
        height: 94% !important;
        height: calc(100% - 30px) !important;
        border: 2px solid #34d399;
        border-top-left-radius: .25rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
      }
      .boxchat-left .boxchat-iframe {
        border-top-left-radius: 0;
        border-top-right-radius: .25rem;
      }
      @media only screen and (max-width: 450px) {
        .boxchat {
          position: fixed !important;
          -ms-transform: scale(0.9, 0.9);
          -webkit-transform: scale(0.9, 0.9);
          transform: scale(0.9, 0.9);
          bottom: -392px !important;
          right: 14px !important;
          transform-origin: right bottom !important;
          max-width: 100%;
        }
        .boxchat-iframe {
          height: 100% !important;
          width: 100% !important;
          bottom: auto !important;
          right: auto !important;
          position: fixed !important;
          -ms-transform: scale(1, 1);
          -webkit-transform: scale(1, 1);
          transform: scale(1, 1);
        }
        .boxchat-opened {
          -ms-transform: none;
          -webkit-transform: none;
          transform: none;
          top: 0 !important;
        }
        .boxchat-opened .boxchat-iframe {
          border: 0;
          border-radius: 0;
        }
        .boxchat-opened .boxchat-header {
          display: none;
        }

        .main-message .content {
          max-height: 85vh !important;
        }
      }
</style>

<style>
.main-message {
    position: relative;
    height: 100%;
    background-color: #e6eaea;
}

.main-message .contact-profile {
    display: flex;
    padding: .5rem;
    background-color: #f5f5f5;
    display: flex;
    justify-content: space-between;
}

.main-message .content {
    overflow-y: scroll;
    max-height: 80%;
}

.main-message .content .message {
    color: #f5f5f5;
    display: flex;
}

.message .message-content {
    color: #01133c;
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
    color: #01133c;
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
    position: absolute;
    bottom: 0;
    width: 100%;
}

.main-message .message-input .text {
    width: 100%;
}
</style>

