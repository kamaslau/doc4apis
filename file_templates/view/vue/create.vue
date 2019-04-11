<template>
  <div id="content" class="container col-xs-12 col-md-8">
    
    <h2>添加企业</h2>
    
    <[[class_name]]Form :value="item" @submit="do_submit" />

  </div>

</template>

<script>
import [[class_name]]Form from '~/components/[[class_name]]/[[class_name]]_form'

export default {
  name: '[[class_name]]Create',
  
  components: { [[class_name]]Form },
  
  head() {
    return {
      title: '[[class_name_cn]]'
    }
  },

  data() {
    return {
      class_name: '[[class_name]]',
      item_id: '',
      item: {}
    }
  }, // end data

  created() {
    console.log('created')
  },
  
  methods: {
    do_submit(item) {
      const api_url = this.class_name + '/create'
      const params = {
        ...item,
        ...this.$store.getters.common_params
      }

      return this.$axios
        .$post(api_url, params)
        .then(result => {
          console.log(result)

          if (result.status === 200) {
            alert('Create OK')
            this.item_id = result.content.id
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
