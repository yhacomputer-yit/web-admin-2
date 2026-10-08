import React, { useEffect, useState } from 'react';
import { Head } from '@inertiajs/react';

export default function Login() {
    const [csrfToken, setCsrfToken] = useState('');
    const [errors, setErrors] = useState({});

    const siteName = 'YHA ACADEMY OF TECHNOLOGY';
    const title = `Login - ${siteName}`;
    const description =
        'Login to your student portal at YHA ACADEMY OF TECHNOLOGY. Access your courses, attendance, assignments, and exam results.';
    const ogImage = '/image/logo/logo.png';

    const keywords = [
        'login',
        'student portal',
        siteName,
        'course access',
        'attendance',
        'assignments',
        'exam results',
    ].join(', ');

    // Get CSRF token from meta tag or window
    useEffect(() => {
        const token =
            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
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
                    Accept: 'application/json',
                },
                body: formData,
            });

            if (response.ok) {
                const data = await response.json();
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.reload();
                }
            } else {
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
            fontFamily: 'system-ui, -apple-system, sans-serif',
        },
        card: {
            background: 'white',
            boxShadow:
                '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            borderRadius: '1rem',
            padding: '2rem',
            width: '100%',
            maxWidth: '400px',
        },
        title: {
            fontSize: '1.875rem',
            fontWeight: 'bold',
            color: '#111827',
            textAlign: 'center',
            marginBottom: '0.5rem',
        },
        subtitle: {
            fontSize: '0.875rem',
            color: '#6b7280',
            textAlign: 'center',
            marginBottom: '2rem',
        },
        inputGroup: {
            marginBottom: '1.5rem',
        },
        label: {
            display: 'block',
            fontSize: '0.875rem',
            fontWeight: '500',
            color: '#374151',
            marginBottom: '0.5rem',
        },
        input: {
            width: '100%',
            padding: '0.75rem',
            border: '1px solid #d1d5db',
            borderRadius: '0.5rem',
            fontSize: '0.875rem',
            transition: 'all 0.2s',
            boxSizing: 'border-box',
        },
        inputError: {
            borderColor: '#ef4444',
        },
        inputFocus: {
            outline: 'none',
            borderColor: '#ff6b01',
            boxShadow: '0 0 0 3px rgba(255, 107, 1, 0.1)',
        },
        error: {
            color: '#ef4444',
            fontSize: '0.875rem',
            marginTop: '0.25rem',
            display: 'flex',
            alignItems: 'center',
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
            justifyContent: 'center',
        },
        buttonHover: {
            transform: 'translateY(-1px)',
            boxShadow: '0 10px 15px -3px rgba(255, 107, 1, 0.3)',
        },
        checkboxGroup: {
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            marginBottom: '1.5rem',
        },
        checkboxLabel: {
            display: 'flex',
            alignItems: 'center',
            fontSize: '0.875rem',
            color: '#374151',
        },
        link: {
            color: '#ff6b01',
            textDecoration: 'none',
            fontSize: '0.875rem',
        },
        errorBox: {
            background: '#fef2f2',
            border: '1px solid #fecaca',
            color: '#dc2626',
            padding: '0.75rem',
            borderRadius: '0.5rem',
            marginBottom: '1.5rem',
            display: 'flex',
            alignItems: 'center',
        },
    };

    return (
        <>
            <Head>
                <title>{title}</title>
                <meta name="description" content={description} />
                <meta name="keywords" content={keywords} />
                <meta property="og:title" content={title} />
                <meta property="og:description" content={description} />
                <meta property="og:image" content={ogImage} />
                <meta property="og:url" content={typeof window !== 'undefined' ? window.location.href : ''} />
                <meta property="og:type" content="website" />
                <meta property="og:site_name" content={siteName} />
                <meta name="twitter:card" content="summary" />
                <meta name="twitter:title" content={title} />
                <meta name="twitter:description" content={description} />
                <meta name="twitter:image" content={ogImage} />
                <link rel="canonical" href={typeof window !== 'undefined' ? window.location.href : ''} />
            </Head>

            <div className="frontend-page" style={styles.container}>
                <div style={styles.card}>
                    <h2 style={styles.title}>Welcome Back</h2>
                    <p style={styles.subtitle}>Sign in to your account to continue</p>

                    {/* Field-level errors */}
                    {Object.keys(errors).filter((k) => k !== 'general').length > 0 && (
                        <div style={styles.errorBox}>
                            <svg
                                width="20"
                                height="20"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                style={{ marginRight: '0.5rem', flexShrink: 0 }}
                            >
                                <path
                                    fillRule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clipRule="evenodd"
                                />
                            </svg>
                            {Object.keys(errors)
                                .filter((k) => k !== 'general')
                                .map((k) => errors[k])
                                .join(' ')}
                        </div>
                    )}

                    {/* General error */}
                    {errors.general && (
                        <div style={styles.errorBox}>
                            <svg
                                width="20"
                                height="20"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                style={{ marginRight: '0.5rem', flexShrink: 0 }}
                            >
                                <path
                                    fillRule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clipRule="evenodd"
                                />
                            </svg>
                            {errors.general}
                        </div>
                    )}

                    <form onSubmit={handleSubmit}>
                        <input type="hidden" name="_token" value={csrfToken} />

                        {/* Username / Email */}
                        <div style={styles.inputGroup}>
                            <label style={styles.label}>Email or Username</label>
                            <input
                                type="text"
                                name="username"
                                autoComplete="username"
                                required
                                placeholder="Enter your email or student username"
                                style={{
                                    ...styles.input,
                                    ...(errors.username ? styles.inputError : {}),
                                }}
                                onFocus={(e) => Object.assign(e.target.style, styles.inputFocus)}
                                onBlur={(e) => {
                                    e.target.style.outline = 'none';
                                    e.target.style.borderColor = errors.username
                                        ? '#ef4444'
                                        : '#d1d5db';
                                    e.target.style.boxShadow = 'none';
                                }}
                            />
                            {errors.username && (
                                <div style={styles.error}>
                                    <svg
                                        width="16"
                                        height="16"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        style={{ marginRight: '0.25rem' }}
                                    >
                                        <path
                                            fillRule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clipRule="evenodd"
                                        />
                                    </svg>
                                    {errors.username}
                                </div>
                            )}
                        </div>

                        {/* Password */}
                        <div style={styles.inputGroup}>
                            <label style={styles.label}>Password</label>
                            <input
                                type="password"
                                name="password"
                                autoComplete="current-password"
                                required
                                placeholder="Enter your password"
                                style={{
                                    ...styles.input,
                                    ...(errors.password ? styles.inputError : {}),
                                }}
                                onFocus={(e) => Object.assign(e.target.style, styles.inputFocus)}
                                onBlur={(e) => {
                                    e.target.style.outline = 'none';
                                    e.target.style.borderColor = errors.password
                                        ? '#ef4444'
                                        : '#d1d5db';
                                    e.target.style.boxShadow = 'none';
                                }}
                            />
                            {errors.password && (
                                <div style={styles.error}>
                                    <svg
                                        width="16"
                                        height="16"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        style={{ marginRight: '0.25rem' }}
                                    >
                                        <path
                                            fillRule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clipRule="evenodd"
                                        />
                                    </svg>
                                    {errors.password}
                                </div>
                            )}
                        </div>

                        {/* Remember me + Forgot password */}
                        <div style={styles.checkboxGroup}>
                            <div style={styles.checkboxLabel}>
                                <input
                                    type="checkbox"
                                    name="remember"
                                    style={{ marginRight: '0.5rem' }}
                                />
                                Remember me
                            </div>
                            <a href="#" style={styles.link}>
                                Forgot password?
                            </a>
                        </div>

                        {/* Submit */}
                        <button
                            type="submit"
                            style={styles.button}
                            onMouseOver={(e) => Object.assign(e.target.style, styles.buttonHover)}
                            onMouseOut={(e) => {
                                e.target.style.transform = 'none';
                                e.target.style.boxShadow = 'none';
                            }}
                        >
                            <svg
                                width="20"
                                height="20"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                style={{ marginRight: '0.5rem' }}
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                />
                            </svg>
                            Sign In
                        </button>

                        <div style={{ textAlign: 'center', marginTop: '1.5rem' }}>
                            <p style={{ fontSize: '0.875rem', color: '#6b7280' }}>
                                Don't have an account?{' '}
                                <a href="" style={styles.link}>
                                    Sign up for free
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}
