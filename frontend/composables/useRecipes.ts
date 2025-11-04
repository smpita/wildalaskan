interface Recipe {
  id: number
  name: string
  description: string
  author_email: string
  slug: string
  ingredients: Array<{ id: number; name: string }>
}

export interface SearchResponse {
  data: Recipe[]
  links: {
    first: string | null
    last: string | null
    prev: string | null
    next: string | null
  }
  meta: {
    current_page: number
    last_page: number
    total: number
    per_page: number
    from: number | null
    to: number | null
  }
}

export const useRecipes = () => {
  const config = useRuntimeConfig()
  const apiBase = config.public.apiBase

  const searchRecipes = (params: {
    email?: string
    keyword?: string
    ingredients?: string[]
    page?: number
    per_page?: number
  }): Promise<SearchResponse> => {
    const queryParams = new URLSearchParams()

    if (params.email) queryParams.append('email', params.email)
    if (params.keyword) queryParams.append('keyword', params.keyword)
    if (params.ingredients && params.ingredients.length > 0) {
      params.ingredients.forEach(ingredient => {
        queryParams.append('ingredients[]', ingredient)
      })
    }
    if (params.page) queryParams.append('page', params.page.toString())
    if (params.per_page) queryParams.append('per_page', params.per_page.toString())

    return $fetch<SearchResponse>(`${apiBase}/recipes?${queryParams.toString()}`)
  }

  const getRecipeBySlug = (slug: string) => {
    return $fetch(`${apiBase}/recipes/slug/${slug}`)
  }

  const getRecipeById = (id: number) => {
    return $fetch(`${apiBase}/recipes/${id}`)
  }

  const getIngredients = () => {
    return $fetch<Array<{ id: number; name: string }>>(`${apiBase}/ingredients`)
  }

  return {
    searchRecipes,
    getRecipeBySlug,
    getRecipeById,
    getIngredients
  }
}

