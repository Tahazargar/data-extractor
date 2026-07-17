# Mark (Marketing Community)

Mark is a content-driven platform for managing, processing, and publishing curated information from external websites.  
This repository currently documents the initial phase of the project and the planned system architecture. The platform itself is not yet fully developed.

## Project Goal

The goal of Mark is to build a workflow for:

- managing content sources
- crawling and storing raw content
- processing and validating extracted data
- enriching content using AI
- reviewing and publishing final content
- providing access through a web app, admin panel, and Telegram bot

## Current Phase

This phase focuses on planning and defining the system structure.  
The project is not yet production-ready and implementation is still in progress.

## Planned Features

### 1. Source Management
Manage content sources, settings, and crawling configurations.

### 2. Crawling
- Run scheduled or manual crawl jobs
- Fetch content from external websites
- Store raw content for later processing

### 3. Content Processing
- Extraction of meaningful data from raw pages
- Validation of collected data
- Deduplication of repeated content

### 4. Content Enrichment
- AI-powered content enhancement
- Revision history for enriched content
- Optional human review before publishing

### 5. Content Management
- Review and approval workflow
- Publishing content
- Search and query capabilities

## Planned Access Methods

The platform will be accessible through:

- Web application
- Admin panel
- Telegram bot

OTP authentication is not planned for this project.

## Planned Tech Stack

- **Backend:** Laravel 13
- **Frontend:** Vue.js
- **Database:** MySQL
- **Cache / Queue / Temporary Data:** Redis
- **Web Server:** Nginx
- **AI Integration:** OpenAI

## System Overview

The expected flow of the system is:

1. Define and manage sources
2. Crawl content from external websites
3. Store raw data
4. Extract and validate content
5. Deduplicate records
6. Enrich content with AI
7. Review and publish final content
8. Provide access through the admin panel, website, and Telegram bot

## External Integrations

The project is expected to integrate with:

- external websites as content sources
- OpenAI for AI-based content enrichment
- Telegram for bot-based access and notifications

## Testing

Automated tests will be written as part of the implementation process.

## License

This project is licensed under the MIT License.

## Status

- Project phase: Planning / Initial development
- Production ready: No
- Documentation: In progress
