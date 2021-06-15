<template>
  <div class="user-list-box">
    <div class="search">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      <input type="text" class="form-control" placeholder="Search" />
    </div>
    <div class="people">

      <contact
        v-for="c, i in conversations" :key="i"
        :conversation="c"
        :active="selected.id === c.id"
        :last="conversations.count() === i + 1"
      />

    </div>
  </div>
</template>

<script>
import collect from 'collect.js'
import Contact from './contact.vue'
import PerfectScrollbar from 'perfect-scrollbar'

export default {
  components: { Contact },

  props: {
    conversations: { type: Object, default: () => collect() }
  },

  data () {
    return { selected: {}, scrollbar: null }
  },

  created () {
    this.$bus.on('select:conversation', conversation => this.selected = conversation)

    this.$nextTick(() => {
      this.scrollbar = new PerfectScrollbar('.people', { suppressScrollX: true })
    })
  }
}
</script>

<style>

</style>
