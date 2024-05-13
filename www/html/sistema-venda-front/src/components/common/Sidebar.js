import React, { useState } from 'react';
import { NavLink } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHome, faUser, faShoppingCart, faCaretDown, faTags, faBoxOpen, faBars } from '@fortawesome/free-solid-svg-icons';

function Menu() {
    const [isMenuOpen, setIsMenuOpen] = useState(true);
    const [isCadastroOpen, setIsCadastroOpen] = useState(false);

    const toggleMenu = () => {
      setIsMenuOpen(!isMenuOpen);
      if(isMenuOpen) {
          setIsCadastroOpen(false);
      }
    };

    const toggleCadastro = () => {
        setIsCadastroOpen(!isCadastroOpen);
    };

    return (
        <div style={{ backgroundColor: '#005780', color: 'white', width: '250px', height: '100vh', overflow: 'auto' }}>
            <div style={{ padding: '20px', fontSize: '24px', fontWeight: 'bold', borderBottom: '1px solid gray', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              {isMenuOpen && <span>SoftExpert</span>}
              <FontAwesomeIcon icon={faBars} onClick={toggleMenu} style={{ cursor: 'pointer' }} />
            </div>
            {isMenuOpen && (
            <ul style={{ listStyleType: 'none', padding: 0 }}>
                <li>
                    <NavLink exact to="/" style={{ padding: '15px', display: 'flex', alignItems: 'center', textDecoration: 'none', color: 'white' }}>
                        <FontAwesomeIcon icon={faHome} style={{ marginRight: '10px' }} />
                        Painel
                    </NavLink>
                </li>
                <li style={{ padding: '15px', cursor: 'pointer' }} onClick={toggleCadastro}>
                    <FontAwesomeIcon icon={faCaretDown} style={{ marginRight: '10px' }} />
                    Cadastro
                    {isCadastroOpen && (
                        <ul style={{ listStyleType: 'none', paddingLeft: '20px' }}>
                            <li style={{ padding: '10px' }}>
                                <NavLink to="/product-types/new" style={{ display: 'flex', alignItems: 'center', textDecoration: 'none', color: 'white' }}>
                                    {/* <FontAwesomeIcon icon={faTags} style={{ marginRight: '10px' }} /> */}
                                    - Tipo de Produto
                                </NavLink>
                            </li>
                            <li style={{ padding: '10px' }}>
                                <NavLink to="/products/new" style={{ display: 'flex', alignItems: 'center', textDecoration: 'none', color: 'white' }}>
                                    {/* <FontAwesomeIcon icon={faBoxOpen} style={{ marginRight: '10px' }} /> */}
                                    - Produto
                                </NavLink>
                            </li>
                            <li style={{ padding: '10px' }}>
                                <NavLink to="/sales/new" style={{ display: 'flex', alignItems: 'center', textDecoration: 'none', color: 'white' }}>
                                    {/* <FontAwesomeIcon icon={faShoppingCart} style={{ marginRight: '10px' }} /> */}
                                    - Venda
                                </NavLink>
                            </li>
                        </ul>
                    )}
                </li>
                <li>
                    <NavLink exact to="/product-types" style={{ padding: '15px', display: 'flex', alignItems: 'center', textDecoration: 'none', color: 'white' }}>
                        <FontAwesomeIcon icon={faTags} style={{ marginRight: '10px' }} />
                        Tipos de Produtos
                    </NavLink>
                </li>
                <li>
                    <NavLink exact to="/products" style={{ padding: '15px', display: 'flex', alignItems: 'center', textDecoration: 'none', color: 'white' }}>
                        <FontAwesomeIcon icon={faBoxOpen} style={{ marginRight: '10px' }} />
                        Produtos
                    </NavLink>
                </li>
                <li>
                    <NavLink exact to="/sales" style={{ padding: '15px', display: 'flex', alignItems: 'center', textDecoration: 'none', color: 'white' }}>
                        <FontAwesomeIcon icon={faShoppingCart} style={{ marginRight: '10px' }} />
                        Vendas
                    </NavLink>
                </li>
            </ul>
          )}
        </div>
    );
}

export default Menu;



