<template>
  <div id="content" class="container col-xs-12 col-md-8">

    <div v-if="item === {}" class="item-invalid">
      <p>没有符合条件的数据</p>
    </div>

    <div v-else class="item-detail">
      [[content]]
    </div>

  </div>
</template>

<script>
export default {
  name: 'BizDetail',

  head() {
    return {
      title: this.item.brief_name || '企业详情',
      meta: [
        { name: 'description', content: this.item.name || process.env.description }
      ]
    }
  },

  data() {
    return {
      class_name: 'biz',
      id_name: 'biz_id',
      id: this.$route.query.id,
      
      item: {},
    }
  },

  watch: {
    '$route': 'fetch_data' // 若当前页面内路由更新了，重新获取数据
  },
  
  asyncData(context) {
    // console.log(context)
    const api_url = 'biz/detail'
    const params = { 'id': context.query.id, ...context.store.getters.common_params }

    return context.app.$axios
      .$post(api_url, params)
      .then(result => {
        // console.log(result)

        if (result.status === 200) {
          return {
            item: { ...result.content }
          }
        }
      })
      .catch(error => {
        context.error(error)
      })
  },

  created() {
    // console.clear()
    // this.item = this.$store.dispatch('biz/fetchItem', this.$route.query.id)
    console.log('item: ', this.item)
  },
  
  methods: {
    // 获取数据
    fetch_data() {
      console.log('fetch_data')
      // this.id = this.$route.query.id
    },

    // 推荐人选
    recommend(post_id) {
      console.log(post_id)
    }
  }
}
</script>

<style scoped>

</style>
