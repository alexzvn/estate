<template>
  <div v-if="conversation" class="person" :class="{'border-none': last, active}" @click="$bus.emit('select:conversation', conversation)">

    <div class="user-info">
      <div class="f-head">
        <img src="https://cdn.icon-icons.com/icons2/2643/PNG/512/male_boy_person_people_avatar_icon_159358.png" alt="avatar">
      </div>

      <div class="f-body">
        <div class="meta-info">
          <span class="user-name" data-name="Grace Roberts">{{ conversation.topic.name }}</span>
          <span class="user-meta-time"> --:-- PM</span>
        </div>
        <span class="preview">{{ sender }}: {{ message.content }}</span>
      </div>
    </div>
  
  </div>
</template>

<script>
export default {
  props: {
    conversation: { type: Object, default: () => {} },
    last: { type: Boolean, default: false},
    active: { type: Boolean, default: false}
  },

  data () {
    return {
      message: this.conversation.message
    }
  },

  watch: {
    conversation (conversation) {
      this.message = conversation.message
    }
  },

  computed: {
    sender () {
      return this.conversation.sender_id == this.conversation.topic_id
        ? 'Khách'
        : this.conversation.sender.name
    }
  }
}
</script>

<style scoped>
.person.active {
  background-color: #e0eefb;
}
</style>
