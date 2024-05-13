import React, { useState, useEffect } from 'react';
import Modal from 'react-bootstrap/Modal';
import Button from 'react-bootstrap/Button';
import 'bootstrap/dist/css/bootstrap.min.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTrash } from '@fortawesome/free-solid-svg-icons';
import { fetchProducts } from '../../services/productService';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { createSale } from '../../services/saleService';
import { useNavigate } from 'react-router-dom';

const AddSale = () => {
  const [cart, setCart] = useState([]);
  const [quantity, setQuantity] = useState(1);
  const [barcode, setBarcode] = useState('');
  const [valueReceived, setValueReceived] = useState('');
  const [change, setChange] = useState(0);
  const [productSale, setProductSale] = useState([]);
  const [showModal, setShowModal] = useState(false);

  const navigate = useNavigate();

  useEffect(() => {
    fetchProducts()
      .then(response => {
        setProductSale(response.data.data);
      })
      .catch(error => console.error('Error fetching products:', error));
  }, []);

  const handleRemoveFromCart = (itemId) => {
    const updatedCart = cart.filter(item => item.product_id !== itemId);
    setCart(updatedCart);
  };

  const handleKeyPress = (e) => {
    if (e.key === 'Enter') {
      const product = productSale.find(product => product.ean === barcode);
      if (product) {
        const itemIndex = cart.findIndex(item => item.product_id === product.id);
        if (itemIndex !== -1) {
          const updatedCart = [...cart];
          updatedCart[itemIndex].quantity += parseInt(quantity);
          setCart(updatedCart);
        } else {
          const item = {
            quantity: parseInt(quantity),
            product_id: product.id
          };
          setCart([...cart, item]);
        }
        setBarcode('');
        setQuantity(1);
      }
    }
  };

  const getTotalPrice = () => {
    return cart.reduce((total, item) => {
      const product = productSale.find(p => p.id === item.product_id);
      return total + product.price * item.quantity;
    }, 0);
  };

  const getTotalTax = () => {
    return cart.reduce((total, item) => {
      const product = productSale.find(p => p.id === item.product_id);
      return total + product.price * item.quantity * product.product_type.tax / 100;
    }, 0);
  };

  const handleFinalizeSale = () => {
    const totalPrice = getTotalPrice();
    const received = parseFloat(valueReceived);
    if (received >= totalPrice) {
      const changeValue = received - totalPrice;
      setChange(changeValue.toFixed(2));
      const productsObject = { products: cart };
      createSale(productsObject)
        .then(() => {
          toast.success("Venda Salva com sucesso");
          setShowModal(false);
          setCart([]);
          setValueReceived('');
          setChange(0);
          navigate('/sales');
        })
        .catch(error => console.error('Error during sale creation:', error));
    } else {
      toast.warning("Valor insuficiente");
    }
  };

  const handleModalClose = () => setShowModal(false);
  const handleModalShow = () => setShowModal(true);

  const handleValueReceivedChange = (e) => {
    const receivedValue = e.target.value;
    setValueReceived(receivedValue);

    if (!isNaN(receivedValue) && receivedValue !== '') {
      const totalPrice = getTotalPrice();
      const received = parseFloat(receivedValue);
      if (received >= totalPrice) {
        const changeValue = received - totalPrice;
        setChange(changeValue.toFixed(2));
      } else {
        setChange(0);
      }
    } else {
      setChange(0);
    }
  };

  const handleQuantityChange = (value) => {
    if (quantity > 0) {
      setQuantity(quantity + value);
    } else if (value > 0) {
      setQuantity(quantity + value);
    }
  };

  return (
    <div className="container">
      <div className="row justify-content-center">
        <div className="col-md-6 border p-3 rounded">
          <div className="d-flex justify-content-end">
            <button className="btn btn-info" onClick={handleModalShow}>Ver Carrinho</button>
          </div>
          <br/>
          <div className="d-flex justify-content-center align-items-center mb-3">
            <h3>Adicionar Venda</h3>
          </div>
          <div className="mb-3">
            <label htmlFor="quantity" className="form-label">Quantidade:</label>
            <div className="input-group">
              <button className="btn btn-outline-secondary" type="button" onClick={() => handleQuantityChange(quantity - 1)}>-</button>
              <input
                type="number"
                className="form-control"
                id="quantity"
                value={quantity}
                onChange={(e) => setQuantity(parseInt(e.target.value))}
              />
              <button className="btn btn-outline-secondary" type="button" onClick={() => handleQuantityChange(quantity + 1)}>+</button>
            </div>
          </div>
          <div className="mb-3">
            <label htmlFor="ean" className="form-label">Código do Produto:</label>
            <input
              type="text"
              className="form-control"
              placeholder="Digite o código e pressione enter"
              value={barcode}
              onChange={(e) => setBarcode(e.target.value)}
              onKeyPress={handleKeyPress}
            />
          </div>
          <h3 className="text-center">Totais</h3>
          <div className="row">
            <div className="col-6 mb-2">
              <label htmlFor="totalPrice" className="form-label">Total:</label>
              <input
                type="text"
                className="form-control"
                id="totalPrice"
                value={`R$ ${getTotalPrice().toFixed(2)}`}
                disabled
              />
            </div>
            <div className="col-6 mb-2">
              <label htmlFor="totalTax" className="form-label">Impostos:</label>
              <input
                type="text"
                className="form-control"
                id="totalTax"
                value={`R$ ${getTotalTax().toFixed(2)}`}
                disabled
              />
            </div>
          </div>
          <div className="mb-3">
            <label htmlFor="valueReceived" className="form-label">Recebido:</label>
            <input
              type="text"
              className="form-control me-2"
              id="valueReceived"
              value={valueReceived}
              onChange={handleValueReceivedChange}
              disabled={getTotalPrice() <= 0}
            />
            <label htmlFor="change" className="form-label">Troco:</label>
            <input
              type="text"
              className="form-control mb-2"
              id="change"
              value={change}
              disabled
            />
          </div>
          <div className="d-grid">
            <button className="btn btn-success" onClick={handleFinalizeSale} disabled={getTotalPrice() <= 0}>Finalizar Venda</button>
          </div>
          <Modal show={showModal} onHide={handleModalClose}>
            <Modal.Header closeButton>
              <Modal.Title>Carrinho de Compras</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              <table className="table">
                <thead>
                  <tr>
                    <th scope="col">Produto</th>
                    <th scope="col">Preço Unitário</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Total</th>
                    <th scope="col">Ação</th>
                  </tr>
                </thead>
                <tbody>
                  {cart.map(item => {
                    const product = productSale.find(p => p.id === item.product_id);
                    return (
                      <tr key={item.product_id}>
                        <td>{product.description}</td>
                        <td>R$ {product.price.toFixed(2)}</td>
                        <td>{item.quantity}</td>
                        <td>R$ {(product.price * item.quantity).toFixed(2)}</td>
                        <td>
                          <button className="btn btn-danger btn-sm" onClick={() => handleRemoveFromCart(item.product_id)}>
                            <FontAwesomeIcon icon={faTrash} />
                          </button>
                        </td>
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </Modal.Body>
            <Modal.Footer>
              <Button variant="secondary" onClick={handleModalClose}>Fechar</Button>
              <Button variant="primary" onClick={handleFinalizeSale}>Finalizar Venda</Button>
            </Modal.Footer>
          </Modal>
        </div>
      </div>
    </div>
  );
};

export default AddSale;