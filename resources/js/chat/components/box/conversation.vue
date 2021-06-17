<template>
<div ref="self" class="chat-conversation-box ps" style="height: calc(100% - 133px);">
  <div class="chat-conversation-box-scroll">
      <div class="chat active-chat">
          <!-- <div class="conversation-start">
              <span>Today, 6:48 AM</span>
          </div> -->
          <message v-for="m, i in messages" :key="i" :message="m" />
      </div>
  </div>
</div>
</template>

<script>
import collect from 'collect.js'
import message from './message.vue'
import PerfectScrollbar from 'perfect-scrollbar'

export default {
  components: { message },

  props: {
    topic: { type: Object, default: null },
    messages: { type: Object, default: () => collect() }
  },

  data () {
    return {
      scrollbar: {
        chat: null
      }
    }
  },

  watch: {
    topic () {
      this.scrollToTop()
    }
  },

  created () {
    this.$nextTick(() => {
      this.initScrollBar()
      this.scrollToBottom()
    })
  },

  updated () {
    this.$nextTick(() => {
      this.scrollToBottom()
    })
  },

  methods: {
    initScrollBar () {
      this.scrollbar.chat = new PerfectScrollbar('.chat-conversation-box', { suppressScrollX : true })
    },

    scrollToTop () {
      this.$refs.self.scrollTop = 0
    },

    scrollToBottom (delay = 100) {
      setTimeout(() => {
        this.$refs.self.scrollTop = this.$refs.self.scrollHeight
      }, delay)
    }
  }
}
</script>
