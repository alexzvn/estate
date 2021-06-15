<template>
  <div class="chat-system">
    <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>

    <Sidebar :conversations="conversations" />

    <div class="chat-box">

      <div v-if="!topic" class="chat-not-selected">
        <p> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> Chọn người bất kì để chat</p>
      </div>

      <div v-if="topic" class="chat-box-inner" :style="{height: '100%'}">
        <Meta :topic="topic" />
        <Conversation :topic="topic" :messages="currentMessages" />
        <Input />
      </div>

    </div>
  </div>
</template>

<script>
import Meta from './components/box/meta.vue'
import Conversation from './components/box/conversation.vue'
import Input from './components/box/input.vue'
import Sidebar from './components/sidebar.vue'
import collect from 'collect.js'

export default {
  components: { Meta, Conversation, Input, Sidebar },

  data () {
    return {
      channel: window.Echo.private('chat'),
      conversations: collect(window._conversations).sortByDesc('updated_at'),

      fetchManager: collect(),
      messages: collect([]),

      topic: null,
      currentMessages: collect([])
    }
  },

  watch: {
    topic (topic) {
      if (this.fetchManager.where('id', topic.id).isNotEmpty()) {
        return this.filterTopicMessages()
      }

      this.fetchTopicMessages(topic).then(this.filterTopicMessages)
    },

    messages () {
      this.filterTopicMessages()
    }
  },

  created () {
    this.$bus.on('select:conversation', conversation => {
      this.topic = conversation.topic
    })

    this.$bus.on('message:input', this.sendMessage)

    this.channel.listen('message:created', ({content, topic, sender, conversation}) => {
      const message = {...content, topic, sender}

      conversation.message = message

      this.messages = this.messages.push(message).unique('id')

      this.conversations = this.conversations.push(conversation).sortByDesc('updated_at').unique('id')
    })
  },

  methods: {
    sendMessage (text) {
      const topic = this.topic

      if (! topic || ! topic.id) return;

      const token = window.document.querySelector('meta[name=csrf-token]').getAttribute('content')

      fetch('/manager/chat/' + topic.id + '/messages', {
        headers: { 'content-type': 'application/json', 'X-CSRF-TOKEN': token },
        method: 'POST',
        body: JSON.stringify({ message: text })
      }).then(res => {
        if (!res.ok) {
          throw new Error(res.statusText)
        }
      })
      .catch(() => {
        window.navigator.onLine
          ? window.alert("Không thể gửi tin nhắn, xin hãy thử lại sau ít phút")
          : window.alert("Bạn đang mất kết nối nên không thể gửi tin nhắn")
      })
    },

    fetchTopicMessages (topic) {
      return fetch('/manager/chat/' + topic.id + '/messages')
        .then(response => response.json())
        .then(res => {
          this.messages.push(...res.data)
          this.fetchManager.push(topic)
        })
    },

    filterTopicMessages () {
      if (this.topic === null) {
        this.currentMessages = collect()
      }

      this.currentMessages = collect(this.messages)
        .filter(message => message.topic_id == this.topic.id)
        .sortBy('created_at')
        .unique('id')
    }
  },
}
</script>
