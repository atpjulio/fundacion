export const formatCurrency = (locales, currency, fractionDigits, number) => {
  var formatted = new Intl.NumberFormat(locales, {
    style: 'currency',
    currency: currency,
    minimumFractionDigits: fractionDigits
  }).format(number)
  return formatted
}

export const formatCOP = number => {
  return formatCurrency('es-CO', 'COP', 2, number)
}

export const shorten = (string = '', maxLength = 100) => {
  if (string.length > maxLength) {
    return string.substring(0, maxLength - 4) + '...'
  }
  return string
}
