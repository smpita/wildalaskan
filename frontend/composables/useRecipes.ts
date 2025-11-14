interface Ingredient {
  id: number
  name: string
  amount: string
  unit: string
}
interface Step {
  description: string
  step_number: number
}
export interface Recipe {
  id: number
  name: string
  description: string
  author_email: string
  slug: string
  steps: Step[]
  ingredients: Ingredient[]
}
export interface SearchResponse {
  data: Recipe[]
  links: {
    first?: string
    last?: string
    prev?: string
    next?: string
  }
  meta: {
    current_page: number
    last_page: number
    total: number
    per_page: number
    from?: number
    to?: number
  }
}

export const useRecipes = () => {
  const config = useRuntimeConfig()
  const apiBase = config.public.apiBase
  const headers = {
    Accept: 'application/json'
  }

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

    return $fetch<SearchResponse>(`${apiBase}/recipes?${queryParams.toString()}`, { headers })
  }

  const getRecipeBySlug = (slug: string) => {
    return $fetch(`${apiBase}/recipes/${slug}`, { headers })
  }

  const getIngredients = () => {
    return $fetch<{ data: Array<{ id: number; name: string }> }>(`${apiBase}/ingredients`, { headers })
      .then(response => response.data)
  }

  return {
    searchRecipes,
    getRecipeBySlug,
    getIngredients
  }
}

