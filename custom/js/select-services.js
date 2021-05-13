import $ from 'jquery'
import { storeCurrency } from './utils/currency'

const getCheckedCount = (checkboxes) => Array.from(checkboxes)
    .reduce((i, c) => i + (c.checked ? 1 : 0), 0)

const getCheckedValues = (checkboxes) => Array.from(checkboxes)
    .filter((checkbox) => checkbox.checked)
    .map((checkbox) => parseFloat(checkbox.getAttribute('data-price')) || 0)

/**
 * @param {HTMLElement} form
 */
function initSelectServices(form) {
  const basePrice = parseFloat(form.getAttribute('data-base-price')) || 0

  const checkboxes = form.querySelectorAll('input[type="checkbox"][name="ep_services[]"]')
  const totalElement = form.querySelector('[data-text-total]')
  const itemCountElement = form.querySelector('[data-text-count]')

  const checkedCount = () => getCheckedCount(checkboxes)

  const getSelectedTotal = () => {
    const prices = getCheckedValues(checkboxes)

    return prices.reduce((total, price) => total + price, 0)
  }

  const handleCheckboxChange = (e) => {
    let count = checkedCount()
    // submitButton.disabled = count === 0

    if (itemCountElement) {
      itemCountElement.textContent = count
    }

    if (totalElement) {
      totalElement.innerHTML = storeCurrency.formatAmount(
          basePrice + getSelectedTotal()
      )
    }

    if (e) {
      handleClassState(e.currentTarget)
    }
  }

  const handleClassState = (checkbox) => {
    let parent = $(checkbox).closest('.epService__item')[0]
    if (!parent) {
      return
    }

    if (checkbox.checked) {
      parent.classList.add('is-active')
    } else {
      parent.classList.remove('is-active')
    }
  }

  // Init the listeners.
  handleCheckboxChange()
  checkboxes.forEach(element => {
    handleClassState(element)
    element.addEventListener('change', handleCheckboxChange)
  })

  return () => {
    checkboxes.forEach(element => {
      element.removeEventListener('change', handleCheckboxChange)
    })
  }
}

$(function() {
  let elements = document.querySelectorAll('form.epService')

  for (let element of elements) {
    initSelectServices(element)
  }
})
