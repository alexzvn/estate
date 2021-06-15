<template>
  <div class="bubble" :class="position">
    {{ message.content }}
  </div>
</template>

<script>
export default {
  props: {
    message: { type: Object }
  },

  computed: {
    position () {
      const m = this.message

      return m.topic_type == m.sender_type && m.topic_id == m.sender_id
        ? {'you': true}
        : {'me' : true}
    }
  }
}
</script>

<style>

</style>
