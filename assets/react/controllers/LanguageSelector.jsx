import React, { useState, useEffect } from "react";
import axios from "axios";

const LanguageSelector = () => {
    const [languages, setLanguages] = useState([]);
    const [activeLanguage, setActiveLanguage] = useState(null);
    const [showDropdown, setShowDropdown] = useState(false);

    useEffect(() => {
        axios.get("/api/api_languages")
            .then(response => {
                let languages = response.data.member;
                const activeLang = languages.find(lang => lang.active) || languages[0];
                languages = [activeLang, ...languages.filter(lang => lang.id !== activeLang.id)];
                setLanguages(languages);
                setActiveLanguage(activeLang);
            })
            .catch(error => {
                console.error("Error fetching languages:", error);
            });
    }, []);

    const handleLanguageChange = (newLanguage) => {
        if (newLanguage.id !== activeLanguage.id) {
            // Désactiver l'ancienne langue
            axios.patch(`/api/api_languages/${activeLanguage.id}`,
                {
                    code: activeLanguage.code,
                    name: activeLanguage.name,
                    isActive: false
                }, {
                    headers: {
                        'Accept': 'application/ld+json',
                        'Content-Type': 'application/merge-patch+json'
                    }
                }).then(() => {
                // Activer la nouvelle langue
                axios.patch(`/api/api_languages/${newLanguage.id}`,
                    {
                        code: newLanguage.code,
                        name: newLanguage.name,
                        isActive: true
                    }, {
                        headers: {
                            'Accept': 'application/ld+json',
                            'Content-Type': 'application/merge-patch+json'
                        }
                    }).then(() => {
                    // Mettre à jour l'état local
                    setActiveLanguage(newLanguage);
                    setShowDropdown(false);
                    setLanguages(prevLanguages =>
                        prevLanguages.map(lang => ({
                            ...lang,
                            isActive: lang.id === newLanguage.id
                        }))
                    );
                }).catch(error => {
                    console.error("Erreur lors de l'activation de la langue :", error);
                });
            }).catch(error => {
                console.error("Erreur lors de la désactivation de la langue :", error);
            });
        }
    };

    return (
        <div className="relative inline-block text-left">
            <button
                className="bg-gray-300 px-4 py-2 rounded-md flex items-center gap-2"
                onClick={() => setShowDropdown(!showDropdown)}
            >
                {activeLanguage?.code.toUpperCase()} ▼
            </button>

            {showDropdown && (
                <div className="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg">
                    {languages.map((lang) => (
                        <label
                            key={lang.code}
                            className="flex items-center px-4 py-2 hover:bg-gray-200 cursor-pointer"
                            onClick={() => handleLanguageChange(lang)}
                        >
                            <input
                                type="checkbox"
                                checked={activeLanguage?.id === lang.id}
                                readOnly
                                className="mr-2"
                            />
                            {lang.code.toUpperCase()}
                        </label>
                    ))}
                </div>
            )}
        </div>
    );
};

export default LanguageSelector;