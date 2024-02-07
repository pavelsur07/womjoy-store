function updateUrl(filterValues, template) {
  const currentUrl = window.location.href
  const urlParts = currentUrl.split('?')
  const baseUrl = urlParts[0]
  let queryParams = {}
  if (urlParts.length > 1) {
    queryParams = parseQueryString(urlParts[1])
  }
  if (filterValues) {
    queryParams[template] = filterValues
  } else {
    delete queryParams[template]
  }
  const queryString = buildQueryString(queryParams)
  return baseUrl + '?' + queryString
}

function parseQueryString(queryString) {
  const query = {}
  const pairs = queryString.split('&')
  for (let i = 0; i < pairs.length; i++) {
    const pair = pairs[i].split('=')
    const key = decodeURIComponent(pair[0])
    query[key] = decodeURIComponent(pair[1] || '')
  }
  return query
}

function buildQueryString(queryParams) {
  const pairs = []
  for (const key in queryParams) {
    // eslint-disable-next-line no-prototype-builtins
    if (queryParams.hasOwnProperty(key)) {
      const value = queryParams[key]
      if (value) {
        pairs.push(encodeURIComponent(key) + '=' + encodeURIComponent(value))
      }
    }
  }
  return pairs.join('&')
}

function generateFilterString() {
  const checkboxes = document.querySelectorAll(
    'input[type="checkbox"].filter-checkbox'
  )
  let filterParams = ''
  for (const checkbox of checkboxes) {
    if (checkbox.checked) {
      filterParams += `_${checkbox.value}`
    }
  }
  return filterParams
}

function getFilterForData() {
  return new FormData(document.querySelector('form.filter__form'))
}

function onFilterChange(event) {
  event.preventDefault()

  const filterString = generateFilterString()

  const filterFormData = new FormData(
    document.querySelector('form.filter__form')
  )

  window.location.search = new URLSearchParams(filterFormData).toString()
  // window.location.search = updateUrl(filterString, 'filter_ids')
}

const filterElements = document.querySelectorAll('.filter-checkbox')

filterElements.forEach(function (filterElement) {
  filterElement.addEventListener('input', onFilterChange)
})
