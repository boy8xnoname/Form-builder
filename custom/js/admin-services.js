import React from 'react'
import { render, useReducer, useState } from '@wordpress/element'
import { Button, TextControl, ToggleControl } from '@wordpress/components'
import { __experimentalNumberControl as NumberControl } from '@wordpress/components'
import { filter, findIndex } from 'lodash'
import domReady from '@wordpress/dom-ready'

import './admin-styles.scss'

function uuidv4() {
  return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(
      /[018]/g,
      c => (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
  )
}

const emptyService = () => ({
  uid: uuidv4(),
  name: '',
  price: '',
  description: '',
  defaultSelected: false
})

const initialState = [emptyService()]

function reducer(state = [], action) {
  switch (action.type) {
    case 'remove':
      return [...filter(state, (a) => a.uid !== action.id)]
    case 'add':
      return [
        ...state,
        emptyService()
      ]
    case 'update':
      let index = findIndex(state, { 'uid': action.id })
      let service = state[index]

      state.splice(index, 1, {
        ...service,
        [action.key]: action.value
      })

      return [...state]
    default:
      throw new Error()
  }
}

function ServiceElement({
  index,
  uid,
  name,
  price,
  description,
  defaultSelected,
  onRemoveElement,
  onUpdateValue
}) {
  return (
      <li>
        <div className="flexNamePrice">
          <TextControl
              name={`services[${index}][name]`}
              label="Name"
              value={name}
              onChange={(value) => onUpdateValue(uid, 'name', value)}
              required
          />

          <div className="flexNamePrice__Number">
            <NumberControl
                name={`services[${index}][price]`}
                label="Price"
                min={0}
                step={0.01}
                value={price}
                onChange={(value) => onUpdateValue(uid, 'price', value)}
                required
            />
          </div>
        </div>

        <TextControl
            name={`services[${index}][description]`}
            label="Short Description"
            value={description}
            onChange={(value) => onUpdateValue(uid, 'description', value)}
        />

        <ToggleControl
            label="Selected by default?"
            checked={defaultSelected}
            onChange={(value) => onUpdateValue(uid, 'defaultSelected', value)}
        />

        <input type="hidden" name={`services[${index}][uid]`} value={uid}/>

        <input
            type="hidden"
            name={`services[${index}][defaultSelected]`}
            value={defaultSelected ? 1 : 0}
        />

        <a href="#"
           className="removeService"
           onClick={() => onRemoveElement(uid)}>Remove</a>
      </li>
  )
}

function EditServices({ currentBasePrice, currentServices }) {
  const [basePrice, setBasePrice] = useState(currentBasePrice)
  const [services, dispatch] = useReducer(reducer, currentServices ? currentServices : initialState)

  const updateValue = (id, key, value) => {
    dispatch({ type: 'update', id, key, value })
  }

  const servicesElements = services.map((service, index) => {
    return <ServiceElement
        key={service.uid}
        index={index}
        onUpdateValue={updateValue}
        onRemoveElement={() => dispatch({ type: 'remove', id: service.uid })}
        {...service}
    />
  })

  return (
      <div className="ServicesWrapper">
        <NumberControl
            name="base_price"
            label="Base Price"
            value={basePrice}
            min={0}
            step={0.01}
            onChange={(value) => setBasePrice(value)}
        />

        <p className="servicesTitle"><strong>Services</strong></p>
        <ul className="ServicesElements">{servicesElements}</ul>

        <div className="saveAddNew">
          <Button isDefault onClick={() => dispatch({ type: 'add' })}>Add New Service</Button>
          <Button isPrimary type="submit">Save</Button>
        </div>
      </div>
  )
}

domReady(() => {
  let root = document.getElementById('ep-services-root')

  if (root) {
    let services = jQuery(root).data('services')
    let basePrice = root.getAttribute('data-base-price')

    render(<EditServices currentServices={services} currentBasePrice={basePrice}/>, root)
  }
})
