<template>
  <div class="chat-footer chat-active">
    <div class="chat-input">
      <form class="chat-form" action="javascript:void(0);">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
        <input v-model="message" type="text" class="mail-write-box form-control" placeholder="Message" @keyup.enter="send"/>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      message: ''
    }
  },

  methods: {
    send () {
      if (this.message.trim() === '') return

      this.$bus.emit('message:input', this.message.trim())

      this.message = ''
    }
  }
}
</script>

<style>

</style>
