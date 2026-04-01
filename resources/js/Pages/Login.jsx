import React, { useEffect, useState } from 'react';

export default function Login() {
    const [csrfToken, setCsrfToken] = useState('');
    const [errors, setErrors] = useState({});

    useEffect(() => {
        // Get CSRF token from meta tag or window
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                     window.csrfToken ||
                     '';
        setCsrfToken(token);
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            });

            if (response.ok) {
                // Successful login - redirect based on the response
                const data = await response.json();
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.reload();
                }
            } else {
                // Handle errors
                const data = await response.json();
                setErrors(data.errors || { general: 'Login failed. Please try again.' });
            }
        } catch (error) {
            setErrors({ general: 'Network error. Please try again.' });
        }
    };

    const styles = {
        container: {
            minHeight: '100vh',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            background: 'linear-gradient(135deg, #ff6b01 0%, #ff852d 100%)',
            padding: '2rem',
            fontFamily: 'system-ui, -apple-system, sans-serif'
        },
        card: {
            background: 'white',
            boxShadow: '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            borderRadius: '1rem',
            padding: '2rem',
            width: '100%',
            maxWidth: '400px'
        },
        avatar: {
            width: '4rem',
            height: '4rem',
            background: '#ff6b01',
            borderRadius: '50%',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            margin: '0 auto 1.5rem'
        },
        title: {
            fontSize: '1.875rem',
            fontWeight: 'bold',
            color: '#111827',
            textAlign: 'center',
            marginBottom: '0.5rem'
        },
        subtitle: {
            fontSize: '0.875rem',
            color: '#6b7280',
            textAlign: 'center',
            marginBottom: '2rem'
        },
        inputGroup: {
            marginBottom: '1.5rem'
        },
        label: {
            display: 'block',
            fontSize: '0.875rem',
            fontWeight: '500',
            color: '#374151',
            marginBottom: '0.5rem'
        },
        input: {
            width: '100%',
            padding: '0.75rem',
            border: `1px solid ${errors.email || errors.password ? '#ef4444' : '#d1d5db'}`,
            borderRadius: '0.5rem',
            fontSize: '0.875rem',
            transition: 'all 0.2s',
            boxSizing: 'border-box'
        },
        inputFocus: {
            outline: 'none',
            borderColor: '#ff6b01',
            boxShadow: '0 0 0 3px rgba(255, 107, 1, 0.1)'
        },
        error: {
            color: '#ef4444',
            fontSize: '0.875rem',
            marginTop: '0.25rem',
            display: 'flex',
            alignItems: 'center'
        },
        button: {
            width: '100%',
            padding: '0.75rem',
            background: 'linear-gradient(135deg, #ff6b01 0%, #ff852d 100%)',
            color: 'white',
            border: 'none',
            borderRadius: '0.5rem',
            fontSize: '0.875rem',
            fontWeight: '500',
            cursor: 'pointer',
            transition: 'all 0.2s',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center'
        },
        buttonHover: {
            transform: 'translateY(-1px)',
            boxShadow: '0 10px 15px -3px rgba(255, 107, 1, 0.3)'
        },
        checkboxGroup: {
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            marginBottom: '1.5rem'
        },
        checkboxLabel: {
            display: 'flex',
            alignItems: 'center',
            fontSize: '0.875rem',
            color: '#374151'
        },
        link: {
            color: '#ff6b01',
            textDecoration: 'none',
            fontSize: '0.875rem'
        },
        footer: {
            textAlign: 'center',
            fontSize: '0.875rem',
            color: '#6b7280',
            marginTop: '2rem'
        }
    };

    return (
        <div style={styles.container}>
            <div style={styles.card}>
                {/* <div style={styles.avatar}>
                    <svg width="32" height="32" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div> */}
                <h2 style={styles.title}>Welcome Back</h2>
                <p style={styles.subtitle}>Sign in to your account to continue</p>

                {errors.general && (
                    <div style={{
                        background: '#fef2f2',
                        border: '1px solid #fecaca',
                        color: '#dc2626',
                        padding: '0.75rem',
                        borderRadius: '0.5rem',
                        marginBottom: '1.5rem',
                        display: 'flex',
                        alignItems: 'center'
                    }}>
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style={{ marginRight: '0.5rem' }}>
                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                        </svg>
                        {errors.general}
                    </div>
                )}

                <form onSubmit={handleSubmit}>
                    <input type="hidden" name="_token" value={csrfToken} />

                    <div style={styles.inputGroup}>
                        <label style={styles.label}>Email Address</label>
                        <input
                            type="email"
                            name="email"
                            autoComplete="email"
                            required
                            placeholder="Enter your email"
                            style={styles.input}
                            onFocus={(e) => Object.assign(e.target.style, styles.inputFocus)}
                            onBlur={(e) => {
                                e.target.style.outline = 'none';
                                e.target.style.borderColor = errors.email ? '#ef4444' : '#d1d5db';
                                e.target.style.boxShadow = 'none';
                            }}
                        />
                        {errors.email && (
                            <div style={styles.error}>
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style={{ marginRight: '0.25rem' }}>
                                    <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                </svg>
                                {errors.email}
                            </div>
                        )}
                    </div>

                    <div style={styles.inputGroup}>
                        <label style={styles.label}>Password</label>
                        <input
                            type="password"
                            name="password"
                            autoComplete="current-password"
                            required
                            placeholder="Enter your password"
                            style={styles.input}
                            onFocus={(e) => Object.assign(e.target.style, styles.inputFocus)}
                            onBlur={(e) => {
                                e.target.style.outline = 'none';
                                e.target.style.borderColor = errors.password ? '#ef4444' : '#d1d5db';
                                e.target.style.boxShadow = 'none';
                            }}
                        />
                        {errors.password && (
                            <div style={styles.error}>
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style={{ marginRight: '0.25rem' }}>
                                    <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                </svg>
                                {errors.password}
                            </div>
                        )}
                    </div>

                    <div style={styles.checkboxGroup}>
                        <div style={styles.checkboxLabel}>
                            <input
                                type="checkbox"
                                name="remember"
                                style={{ marginRight: '0.5rem' }}
                            />
                            Remember me
                        </div>
                        <a href="#" style={styles.link}>Forgot password?</a>
                    </div>

                    <button
                        type="submit"
                        style={styles.button}
                        onMouseOver={(e) => Object.assign(e.target.style, styles.buttonHover)}
                        onMouseOut={(e) => {
                            e.target.style.transform = 'none';
                            e.target.style.boxShadow = 'none';
                        }}
                    >
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style={{ marginRight: '0.5rem' }}>
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Sign In
                    </button>

                    <div style={{ textAlign: 'center', marginTop: '1.5rem' }}>
                        <p style={{ fontSize: '0.875rem', color: '#6b7280' }}>
                            Don't have an account?{' '}
                            <a href="" style={styles.link}>Sign up for free</a>
                        </p>
                    </div>
                </form>


            </div>
        </div>
    );
}
