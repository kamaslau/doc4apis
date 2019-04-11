<template>
  <div id="content" class="container col-xs-12 col-md-8">
  
    <nuxt-link class="btn btn-outline-primary" :to="'/' + class_name + '/create'"><i class="fal fa-plus"></i></nuxt-link>
  
    <div v-if="items.length === 0">
      <p>没有符合条件的数据</p>
    </div>

    <div v-else>
      <!-- 数据列表 -->
      <ul class="items-list">

        <li v-for="(item, index) in items" :key="index">
          <nuxt-link :to="'/' + class_name + '/detail?id=' + item[id_name]">
            <[[class_name]]Brief :item="item"></[[class_name]]Brief>
          </nuxt-link>
        </li>
  
      </ul>
  
      <!-- 翻页 -->
      <pre>{{ limit }} {{ offset }}</pre>
  
      <nav>
        <ul class="pagination">
          <li class="page-item" @click="fetchData(limit, 0)"><a class="page-link" href="#">首页</a></li>
  
          <li v-if="offset >= limit" class="page-item" @click="fetchData(limit, offset - limit)"><a class="page-link" href="#">上一页</a></li>
          
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          
          <li class="page-item" @click="fetchData(limit, offset + limit)"><a class="page-link" href="#">下一页</a></li>
        </ul>
      </nav>
    </div>

  </div>
</template>

<script>
export default {
  name: '[[class_name]]Index',

  head() {
    return {
      title: '[[class_name_cn]]'
    }
  },
  
  data() {
    return {
      class_name: '[[class_name]]',
      id_name: '[[id_name]]',
      
      items: []
    }
  },

  watch: {
    '$route': function () {
      // console.log(this.$route)
      this.fetchData(this.$route.query.limit, this.$route.query.offset)
    } // 若当前页面内路由更新了，重新获取数据
  },

  asyncData(context) {
    const query_params = {
      limit: context.query.limit || 10,
      offset: context.query.offset || 0
    }
    const api_url = '[[class_name]]/index'
    const params = { ...query_params, ...context.store.getters.common_params }

    return context.$axios
      .$post(api_url, params)
      .then(result => {
        console.log(result)
        
        try {
          if (result.status === 200) {
            return {
              limit: query_params.limit,
              offset: query_params.offset,
              items: [ ...result.content ]
            }
          }
        } catch (error) {
          console.log(error)
          result.status = 500
          result.content.error.message = '服务器错误 ERROR_API'
        }
      })
      .catch(error => {
        context.error(error)
        console.log(error)
      })
  },

  created() {
    // console.clear()
    // console.log(this.items)
  },
  
  methods: {
    // 获取数据
    fetchData(limit, offset) {
      const query_params = {
        limit: limit || this.limit,
        offset: offset || this.offset
      }
      const api_url = this.class_name + '/index'
      const params = { ...query_params, ...this.$store.getters.common_params }

      return this.$axios
        .$post(api_url, params)
        .then(result => {
          console.log(result)
          
          // 若请求成功，保存授权及用户信息到状态
          try {
            if (result.status === 200) {
              this.items = [ ...result.content ]
              this.limit = limit
              this.offset = offset
            }
          } catch (error) {
            console.log(error)
            result.status = 500
            result.content.error.message = '服务器错误 ERROR_API'
          }
        })
        .catch(error => {
          this.error(error)
        })
    }
  }
  
}
</script>

<style scoped>

</style>
