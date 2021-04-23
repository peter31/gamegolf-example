import React, { useEffect, useState } from 'react';
import Navbar from 'react-bootstrap/Navbar';
import Nav from 'react-bootstrap/Nav';
import Button from 'react-bootstrap/Button';
import config from '../../config';
import { observer } from 'mobx-react';
import { useHistory, useLocation } from 'react-router';
import clsx from 'clsx';
import { excludedPages } from '../../Constants/navigation';
import { ReactComponent as Burger } from '../../assets/icons/burger.svg';
import { ReactComponent as Facebook } from '../../assets/icons/facebook.svg';
import { ReactComponent as Flower } from '../../assets/icons/flower.svg';
import { ReactComponent as Instagram } from '../../assets/icons/instagram.svg';

const UnregisteredNavbar = () => {
    const history = useHistory();
    const location = useLocation();
    const [pathname, setPathName] = useState('');
    useEffect(() => {
        setPathName(location.pathname);
    }, [location]);
    return (
        <Navbar
            expand="lg"
            id={'unregister_navbar'}
            className={clsx(excludedPages.includes(pathname.toLowerCase()) ? 'd-none' : 'd-flex')}
        >
            <Navbar.Brand href="/" className={'d-flex justify-content-center'}>
                <img src={require('../../assets/Logo.svg')} alt={'logo'} />
            </Navbar.Brand>
            <Navbar.Toggle aria-controls="basic-navbar-nav">
                <Burger />
            </Navbar.Toggle>
            <Navbar.Collapse id="basic-navbar-nav">
                <Nav className="w-100 evenly">
                    <Nav.Link href={`${config.baseUrlForNavLinks}/shop/`} target={'_blank'}>
                        Packages
                    </Nav.Link>
                    <Nav.Link href={`${config.baseUrlForNavLinks}/blog/`} target={'_blank'}>
                        Blog
                    </Nav.Link>
                    <Nav.Link href={`${config.baseUrlForNavLinks}/solution/`} target={'_blank'}>
                        product info
                    </Nav.Link>
                    <Nav.Link href={`${config.baseUrlForNavLinks}/review/`} target={'_blank'}>
                        reviews
                    </Nav.Link>
                    <Nav.Link href={`${config.baseUrlForNavLinks}/about/`} target={'_blank'}>
                        about
                    </Nav.Link>
                    <Nav.Link href={`${config.baseUrlForNavLinks}/career/`} target={'_blank'}>
                        jobs
                    </Nav.Link>
                    <Nav.Link href={`${config.baseUrlForNavLinks}/faq/`} target={'_blank'}>
                        FAQ
                    </Nav.Link>
                    <Nav.Link href={`${config.baseUrlForNavLinks}/tags/`} target={'_blank'}>
                        tags
                    </Nav.Link>
                </Nav>
                <div className={'navbar_button_container'}>
                    <Button
                        type={'button'}
                        variant={'outline-secondary '}
                        onClick={() => history.push('/logIn')}
                        className={'white'}
                    >
                        Log In
                    </Button>
                    <Button type={'button'} variant={'outline-secondary'} onClick={() => history.push('/signIn')}>
                        Sign Up
                    </Button>
                </div>
                <div
                    className={
                        'd-flex flex-column justify-content-center align-items-center d-lg-none mt-3 footer-info'
                    }
                >
                    <div className={'d-flex justify-content-between social-links'}>
                        <a href={'https://www.facebook.com/votreallure'}>
                            <Facebook />
                        </a>

                        <a href={'https://www.yelp.com/biz/votre-allure-del-mar?osq=votre+allure'}>
                            <Flower />
                        </a>

                        <a href={'https://www.instagram.com/votreallure/'}>
                            <Instagram />
                        </a>
                    </div>
                    <div className={'d-flex flex-column align-self-center justify-content-center contact-info'}>
                        <a href={'mailto:cs@votreallure.com'}>
                            <div className={'footer-text'}>cs@votreallure.com</div>
                        </a>
                        <a href={'tel:+1(877)250-8232'}>
                            <div className={'footer-text'}>(877) 250-8232</div>
                        </a>
                    </div>
                    <div className={'footer-text align-self-end'}>
                        © {new Date().getFullYear()} Votre Allure Labs, LLC
                    </div>
                </div>
            </Navbar.Collapse>
        </Navbar>
    );
};

export default observer(UnregisteredNavbar);
