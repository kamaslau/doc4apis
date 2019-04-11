<template>
  <div id="content" class="container col-xs-12 col-md-8">
    
    <BizForm :value="item" @submit="do_submit" />

  </div>

</template>

<script>
import BizForm from '~/components/biz/biz_form'

export default {
  name: 'BizEdit',
  
  components: { BizForm },

  head() {
    return {
      title: '修改企业信息'
    }
  },

  data() {
    return {
      class_name: 'biz',
      item: {},
      item_id: this.$route.query.id
    }
  }, // end data

  asyncData(context) {
    // console.log(context)
    const api_url = 'biz/detail'
    const params = { ...context.store.getters.common_params, 'id': context.query.id }

    return context.$axios
      .$post(api_url, params)
      .then(result => {
        console.log(result)

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
    console.log(this.item)
  },
  
  methods: {
    do_submit(item) {
      const api_url = this.class_name + '/edit'
      const params = {
        ...item,
        'id': this.item_id,
        ...this.$store.getters.common_params
      }

      return this.$axios
        .$post(api_url, params)
        .then(result => {
          console.log(result)

          if (result.status === 200) {
            alert('Edit OK')
            this.$router.push('/' + this.class_name + '/detail?id=' + this.item_id)
          }
        })
        .catch(error => {
          console.log(error)
        })
    } // end do_submit
  } // end methods
}
</script>

<style scoped>

</style>
